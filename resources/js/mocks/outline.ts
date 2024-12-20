import { Outline, Response } from '@/types';

const getOutline = (index: number): Outline => ({
    id: index,
    name: `name-${index}`,
    url: 'url',
    spending: '123 Kb',
});

export const OUTLINE_MOCK: Response<Outline[]> = {
    data: [getOutline(0), getOutline(1)],
};

let counter = 2;
export const addOutlineMock = (): Response<Outline> => ({
    data: getOutline(++counter),
});
