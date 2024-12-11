import { Outline as OutlineType } from '@/types';
import axios from 'axios';
import { useEffect, useState } from 'react';

export default function Outline() {
    const [items, setItems] = useState<OutlineType[]>([]);
    const [loading, setLoading] = useState<boolean>(false);

    useEffect(() => {
        axios
            .get(route('mini-app.outlines.index'))
            .then((response) => {
                setItems(response.data.data as OutlineType[]);
            })
            .catch(window.Telegram.WebApp.close);
    }, []);

    const handleAdd = () => {
        if (loading) return;

        setLoading(true);

        axios
            .post(route('mini-app.outlines.store'))
            .then((response) => {
                setItems([response.data.data as OutlineType, ...items]);
            })
            .catch((error) => alert(error.message))
            .finally(() => setLoading(false));
    };

    return (
        <>
            <div className="mt-4 flex flex-col">
                {items.length < 100 && (
                    <button
                        onClick={handleAdd}
                        disabled={loading}
                        className="mt-4 flex cursor-pointer items-center justify-center rounded bg-gray-200 p-4 shadow disabled:bg-white"
                    >
                        Generate new key!
                    </button>
                )}
                {items.map((outline: OutlineType, index: number) => (
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
