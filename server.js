const express = require('express');
const mysql = require('mysql2');
const cors = require('cors');
const bcrypt = require('bcryptjs');

const app = express();
app.use(cors());
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

const PORT = process.env.PORT || 3000;
app.listen(PORT, () => {
    console.log(`Server is running on port ${PORT}`);
});
