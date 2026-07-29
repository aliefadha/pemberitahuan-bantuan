const RETRY_MESSAGE =
    'Tanggapan belum dapat diproses. Silakan coba balas kembali beberapa saat lagi.';

function serializedMessageId(message) {
    return message?.id?._serialized ?? null;
}

function quotedMessageIdFromMetadata(message) {
    const quotedId = message?._data?.quotedMsg?.id;

    if (typeof quotedId === 'string') {
        return quotedId;
    }

    return quotedId?._serialized ?? null;
}

function createIncomingMessageHandler({
    laravelAppUrl,
    webhookSecret,
    fetchImpl = global.fetch,
    logger = console
}) {
    const webhookUrl = `${laravelAppUrl.replace(/\/$/, '')}/webhooks/whatsapp/messages`;

    return async function handleIncomingMessage(message) {
        const from = message.from ?? '';
        const isGroup = from.endsWith('@g.us');

        if (message.fromMe || isGroup) {
            return { ignored: true };
        }

        try {
            let quotedMessageId = quotedMessageIdFromMetadata(message);

            if (message.hasQuotedMsg && !quotedMessageId) {
                const quotedMessage = await message.getQuotedMessage();
                quotedMessageId = serializedMessageId(quotedMessage);

                if (!quotedMessageId) {
                    throw new Error('Quoted WhatsApp message has no serialized ID');
                }
            }

            const response = await fetchImpl(webhookUrl, {
                method: 'POST',
                headers: {
                    Accept: 'application/json',
                    'Content-Type': 'application/json',
                    'X-WhatsApp-Webhook-Secret': webhookSecret
                },
                body: JSON.stringify({
                    message_id: serializedMessageId(message),
                    chat_id: from,
                    from,
                    body: message.body ?? '',
                    is_group: false,
                    has_quoted_message: Boolean(message.hasQuotedMsg),
                    quoted_message_id: quotedMessageId,
                    timestamp: message.timestamp ?? null
                })
            });

            const result = await response.json();

            if (!response.ok) {
                throw new Error(
                    `Laravel webhook returned ${response.status}: ${result.message ?? 'unknown error'}`
                );
            }

            if (typeof result.reply === 'string' && result.reply.length > 0) {
                await message.reply(result.reply);
            }

            return result;
        } catch (error) {
            logger.error('Incoming WhatsApp message processing failed:', error);

            try {
                await message.reply(RETRY_MESSAGE);
            } catch (replyError) {
                logger.error('Failed to send WhatsApp retry message:', replyError);
            }

            return { processed: false, error: error.message };
        }
    };
}

module.exports = {
    RETRY_MESSAGE,
    createIncomingMessageHandler,
    quotedMessageIdFromMetadata,
    serializedMessageId
};
