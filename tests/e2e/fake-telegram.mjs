// A stand-in for api.telegram.org: it accepts sendMessage for any bot and lists what it received, without the token.
import { createServer } from 'node:http';

const received = [];

createServer((request, response) => {
    if (request.method === 'GET' && request.url === '/__received') {
        response.writeHead(200, { 'content-type': 'application/json' });
        response.end(JSON.stringify(received));

        return;
    }

    if (request.method === 'POST' && /^\/bot[^/]+\/sendMessage$/.test(request.url ?? '')) {
        let body = '';
        request.on('data', (chunk) => { body += chunk; });
        request.on('end', () => {
            const { chat_id: chatId, text } = JSON.parse(body || '{}');
            received.push({ chatId: String(chatId), text, tokenLength: (request.url ?? '').length - '/bot/sendMessage'.length });
            response.writeHead(200, { 'content-type': 'application/json' });
            response.end(JSON.stringify({ ok: true, result: { message_id: received.length } }));
        });

        return;
    }

    response.writeHead(404);
    response.end();
}).listen(8124, '127.0.0.1');
