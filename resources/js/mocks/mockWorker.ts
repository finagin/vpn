import { setupWorker } from 'msw/browser';
import { http, HttpResponse, delay } from 'msw';
import { OUTLINE_MOCK, addOutlineMock } from './outline';

const handlers = [
    http.get(route('mini-app.outlines.index'), async () => {
        await delay();

        return HttpResponse.json(OUTLINE_MOCK)
    }),
    http.post(route('mini-app.outlines.store'), async () => {
        await delay();

        return HttpResponse.json(addOutlineMock())
    })
]

export const worker = setupWorker(...handlers);
