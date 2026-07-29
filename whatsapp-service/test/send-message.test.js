const assert = require('node:assert/strict');
const { test } = require('node:test');
const { sendTrackedMessage } = require('../send-message');

test('waits for WhatsApp to return the final sent message model', async () => {
    let call;
    const client = {
        sendMessage: async (...args) => {
            call = args;
            return {
                id: { _serialized: 'outbound-message-id' }
            };
        }
    };

    const result = await sendTrackedMessage(
        client,
        '6281234567890@c.us',
        'Broadcast kegiatan'
    );

    assert.deepEqual(call, [
        '6281234567890@c.us',
        'Broadcast kegiatan',
        { waitUntilMsgSent: true }
    ]);
    assert.equal(result.messageId, 'outbound-message-id');
});
