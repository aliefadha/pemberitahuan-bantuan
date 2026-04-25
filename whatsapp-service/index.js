const express = require('express');
const cors = require('cors');
const { Client, LocalAuth } = require('whatsapp-web.js');
const QRCode = require('qrcode');
const path = require('path');
const fs = require('fs');

const app = express();
const PORT = 3001;

app.use(cors());
app.use(express.json());

let qrCodeData = null;
let qrCodeImage = null;
let clientStatus = 'disconnected';
let client = null;

const SESSION_FILE = path.join(__dirname, 'session.json');

function initClient() {
    client = new Client({
        authStrategy: new LocalAuth({
            dataPath: path.join(__dirname, '.wwebjs_auth')
        }),
        puppeteer: {
            headless: true,
            args: ['--no-sandbox', '--disable-setuid-sandbox']
        }
    });

    client.on('qr', async (qr) => {
        console.log('QR Code received');
        qrCodeData = qr;
        
        try {
            qrCodeImage = await QRCode.toDataURL(qr);
            console.log('QR Code generated');
        } catch (err) {
            console.error('QR Code generation failed:', err);
        }
    });

    client.on('ready', () => {
        console.log('WhatsApp Client is Ready!');
        clientStatus = 'ready';
        qrCodeData = null;
        qrCodeImage = null;
    });

    client.on('authenticated', () => {
        console.log('WhatsApp Client Authenticated');
        clientStatus = 'authenticated';
    });

    client.on('auth_failure', (msg) => {
        console.error('Authentication failure:', msg);
        clientStatus = 'auth_failure';
    });

    client.on('disconnected', (reason) => {
        console.log('WhatsApp Client disconnected:', reason);
        clientStatus = 'disconnected';
        client = null;
    });

    client.on('message', (msg) => {
        console.log('Message received:', msg.body);
    });

    client.initialize();
}

initClient();

app.get('/status', (req, res) => {
    res.json({
        status: clientStatus,
        ready: clientStatus === 'ready'
    });
});

app.get('/qr', async (req, res) => {
    if (clientStatus === 'ready') {
        return res.json({
            status: 'ready',
            message: 'WhatsApp is already connected'
        });
    }

    if (!qrCodeImage) {
        return res.json({
            status: clientStatus,
            qr: null
        });
    }

    res.json({
        status: clientStatus,
        qr: qrCodeImage
    });
});

app.post('/send', async (req, res) => {
    const { phone, message } = req.body;

    if (!phone || !message) {
        return res.status(400).json({
            success: false,
            error: 'Phone and message are required'
        });
    }

    if (clientStatus !== 'ready' || !client) {
        return res.status(503).json({
            success: false,
            error: 'WhatsApp client is not ready'
        });
    }

    try {
        const chatId = phone.includes('@c.us') ? phone : `${phone}@c.us`;
        await client.sendMessage(chatId, message);
        
        console.log(`Message sent to ${phone}`);
        res.json({
            success: true,
            message: 'Message sent successfully'
        });
    } catch (error) {
        console.error('Send message failed:', error);
        res.status(500).json({
            success: false,
            error: error.message
        });
    }
});

app.post('/restart', async (req, res) => {
    if (client) {
        await client.destroy();
        client = null;
    }
    clientStatus = 'disconnected';
    qrCodeData = null;
    qrCodeImage = null;
    
    setTimeout(() => {
        initClient();
    }, 1000);

    res.json({
        success: true,
        message: 'WhatsApp client restarting...'
    });
});

app.get('/', (req, res) => {
    res.json({
        service: 'WhatsApp Web Service',
        status: clientStatus,
        endpoints: {
            'GET /status': 'Check connection status',
            'GET /qr': 'Get QR code for authentication',
            'POST /send': 'Send message { phone, message }',
            'POST /restart': 'Restart WhatsApp client'
        }
    });
});

app.listen(PORT, () => {
    console.log(`WhatsApp Service running on http://localhost:${PORT}`);
    console.log(`Status: ${clientStatus}`);
});
