function sendTrackedMessage(client, chatId, content) {
    return client.sendMessage(
        chatId,
        content,
        { waitUntilMsgSent: true }
    ).then((sentMessage) => ({
        sentMessage,
        messageId: sentMessage?.id?._serialized ?? null
    }));
}

module.exports = {
    sendTrackedMessage
};
