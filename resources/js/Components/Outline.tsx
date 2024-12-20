import { Spin } from '@/Components/Spin';
import { Outline as OutlineType } from '@/types';

import { useQuery } from '@tanstack/react-query';
import axios from 'axios';
import { useEffect } from 'react';

export default function Outline() {
    const {
        data: dataInitial,
        isError,
        isFetching: isFetchingInitial,
    } = useQuery({
        queryKey: ['initial'],
        initialData: [],
        queryFn: async (): Promise<OutlineType[]> => {
            const res = await axios.get(route('mini-app.outlines.index'));

            return res?.data?.data ?? [];
        },
    });

    const {
        data: dataRefetch,
        error,
        refetch,
        isFetching: isRefetching,
    } = useQuery({
        queryKey: ['fetch-more'],
        enabled: false,
        initialData: [],
        placeholderData: (previous) => previous,
        queryFn: async (): Promise<OutlineType[]> => {
            const res = await axios.post(route('mini-app.outlines.store'));

            return [...dataRefetch, res?.data?.data];
        },
    });

    useEffect(() => {
        if (error) {
            alert(error);
        }
    }, [error]);

    if (isError) {
        return window.Telegram.WebApp.close();
    }

    const handleAdd = async () =>
        refetch({ throwOnError: true, cancelRefetch: true });

    const items = [...dataInitial, ...dataRefetch];
    const isLoading = isFetchingInitial || isRefetching;

    return (
        <>
            <div className="mt-4 flex flex-col">
                {items.length < 100 && (
                    <button
                        onClick={handleAdd}
                        disabled={isLoading}
                        className="mt-4 flex h-14 cursor-pointer items-center justify-center rounded bg-gray-200 p-4 shadow"
                    >
                        {isLoading ? <Spin /> : 'Generate new key!'}
                    </button>
                )}
                {items.map((outline, index: number) => (
                    <a
                        key={index}
                        href={outline.url}
                        target="_blank"
                        className="mt-4 flex cursor-pointer items-center justify-between rounded bg-white p-4 shadow"
                        rel="noreferrer"
                    >
                        <div>{outline.name}</div>
                        <div>{outline.spending}</div>
                    </a>
                ))}
            </div>
        </>
    );
}
