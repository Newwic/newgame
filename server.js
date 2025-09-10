const express = require('express');
const mysql = require('mysql2');
const cors = require('cors');
const bcrypt = require('bcryptjs');

const app = express();

// Configure CORS to accept requests from the frontend (adjust origin as needed)
app.use(cors({
    origin: true,
    methods: ['GET','POST','PUT','DELETE','OPTIONS'],
    allowedHeaders: ['Content-Type', 'Authorization']
}));

// express + cors will handle OPTIONS preflight automatically
app.use(express.json());

// Database connection
const db = mysql.createPool({
    host: 'localhost',
    user: 'root',
    password: '',
    database: 'newgame',
    charset: 'utf8'
});

// Register endpoint
app.post('/api/register', async (req, res) => {
    const { name, email, password, gender, interests } = req.body;

    if (!name || !email || !password || !gender) {
        return res.status(400).json({ errors: { general: 'กรุณากรอกข้อมูลให้ครบถ้วน' } });
    }

    // Check if email exists
    db.query('SELECT id FROM users WHERE email = ?', [email], async (err, results) => {
        if (err) {
            console.error(err);
            return res.status(500).json({ errors: { general: 'เกิดข้อผิดพลาดบางอย่าง' } });
        }

        if (results.length > 0) {
            return res.status(400).json({ errors: { email: 'อีเมลนี้มีผู้ใช้งานแล้ว' } });
        }

        // Hash password
        const hashedPassword = await bcrypt.hash(password, 10);

        // Insert user
        const newUser = {
            name,
            email,
            password: hashedPassword,
            gender,
            interests: JSON.stringify(interests)
        };

        db.query('INSERT INTO users SET ?', newUser, (err, result) => {
            if (err) {
                console.error(err);
                return res.status(500).json({ errors: { general: 'ไม่สามารถลงทะเบียนได้ในขณะนี้' } });
            }
            res.json({ success: true });
        });
    });
});

// Login endpoint
app.post('/api/login', (req, res) => {
    console.log('Login request from', req.ip, 'body keys:', Object.keys(req.body));
    const { email, password } = req.body;

    if (!email || !password) {
        return res.status(400).json({ message: 'กรุณากรอกอีเมลและรหัสผ่าน' });
    }

    db.query('SELECT id, name, email, password, gender, interests FROM users WHERE email = ?', [email], async (err, results) => {
        if (err) {
            console.error(err);
            return res.status(500).json({ message: 'เกิดข้อผิดพลาดจากเซิร์ฟเวอร์' });
        }

        if (results.length === 0) {
            return res.status(400).json({ message: 'ไม่พบผู้ใช้งานนี้' });
        }

        const user = results[0];

        const match = await bcrypt.compare(password, user.password);
        if (!match) {
            return res.status(400).json({ message: 'อีเมลหรือรหัสผ่านไม่ถูกต้อง' });
        }

        // Create a simple token (for demo). Replace with JWT in production.
        const token = Buffer.from(`${user.id}:${Date.now()}`).toString('base64');

        // return user info without password
        const safeUser = {
            id: user.id,
            name: user.name,
            email: user.email,
            gender: user.gender,
            interests: JSON.parse(user.interests || '[]')
        };

        res.json({ token, user: safeUser });
    });
});

const PORT = process.env.PORT || 3000;
app.listen(PORT, () => {
    console.log(`Server is running on port ${PORT}`);
});
