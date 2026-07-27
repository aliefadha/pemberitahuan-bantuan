const assert = require('node:assert/strict');
const { test } = require('node:test');
const {
    RETRY_MESSAGE,
    createIncomingMessageHandler
} = require('../message-handler');

function response(body, status = 200) {
    return {
        ok: status >= 200 && status < 300,
        status,
        json: async () => body
    };
}

test('forwards a quoted direct message and sends Laravel confirmation', async () => {
    const replies = [];
    let request;
    const handler = createIncomingMessageHandler({
        laravelAppUrl: 'http://laravel.test/',
        webhookSecret: 'shared-secret',
        fetchImpl: async (url, options) => {
            request = { url, options };
            return response({
                processed: true,
                reply: 'Tanggapan berhasil disimpan.'
            });
        }
    });

    const result = await handler({
        id: { _serialized: 'incoming-1' },
        from: '6281234567890@c.us',
        fromMe: false,
        body: 'bersedia',
        timestamp: 123456,
        hasQuotedMsg: true,
        getQuotedMessage: async () => ({
            id: { _serialized: 'outbound-1' }
        }),
        reply: async (message) => replies.push(message)
    });

    assert.equal(request.url, 'http://laravel.test/webhooks/whatsapp/messages');
    assert.equal(
        request.options.headers['X-WhatsApp-Webhook-Secret'],
        'shared-secret'
    );
    assert.deepEqual(JSON.parse(request.options.body), {
        message_id: 'incoming-1',
        chat_id: '6281234567890@c.us',
        from: '6281234567890@c.us',
        body: 'bersedia',
        is_group: false,
        has_quoted_message: true,
        quoted_message_id: 'outbound-1',
        timestamp: 123456
    });
    assert.deepEqual(replies, ['Tanggapan berhasil disimpan.']);
    assert.equal(result.processed, true);
});

test('ignores messages from groups', async () => {
    let called = false;
    const handler = createIncomingMessageHandler({
        laravelAppUrl: 'http://laravel.test',
        webhookSecret: 'shared-secret',
        fetchImpl: async () => {
            called = true;
            return response({});
        }
    });

    const result = await handler({
        from: '12345@g.us',
        fromMe: false
    });

    assert.deepEqual(result, { ignored: true });
    assert.equal(called, false);
});

test('sends an honest retry message when Laravel fails', async () => {
    const replies = [];
    const handler = createIncomingMessageHandler({
        laravelAppUrl: 'http://laravel.test',
        webhookSecret: 'shared-secret',
        fetchImpl: async () => response({ message: 'Server error' }, 500),
        logger: { error: () => {} }
    });

    const result = await handler({
        id: { _serialized: 'incoming-1' },
        from: '6281234567890@c.us',
        fromMe: false,
        body: 'bersedia',
        hasQuotedMsg: false,
        reply: async (message) => replies.push(message)
    });

    assert.equal(result.processed, false);
    assert.deepEqual(replies, [RETRY_MESSAGE]);
});
