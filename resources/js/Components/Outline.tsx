import { humanSize } from '@/helpers';
import { Outline as OutlineType } from '@/types';
import axios from 'axios';
import { FC, useEffect, useState } from 'react';

export const Outline: FC = () => {
    const [items, setItems] = useState<OutlineType[]>([]);
    const [loading, setLoading] = useState<boolean>(false);
    const [loaded, setLoaded] = useState<boolean>(false);

    useEffect(() => {
        axios
            .get(route('mini-app.outlines.index'))
            .then((response) => setItems(response.data.data as OutlineType[]))
            .catch(({ status }) =>
                status === 401
                    ? (location.href = 'https://t.me/finaginbot?startapp=vpn')
                    : window.Telegram.WebApp.close(),
            )
            .finally(() => setLoaded(true));
    }, []);

    const handleAdd = () => {
        if (loading) return;

        setLoading(true);

        axios
            .post(route('mini-app.outlines.store'))
            .then((response) => {
                setItems([response.data.data as OutlineType, ...items]);
            })
            .catch((error) =>
                alert(error?.response?.data?.message ?? error.message),
            )
            .finally(() => setLoading(false));
    };

    return (
        <>
            <section>
                <div className="mx-auto max-w-screen-xl px-4 py-8 text-center lg:py-16">
                    <h1 className="mb-4 text-4xl font-extrabold leading-none tracking-tight text-gray-900 md:text-5xl lg:text-6xl dark:text-white">
                        <Limits
                            loading={!loaded}
                            spending={items.reduce(
                                (acc, item) => acc + item.spending,
                                0,
                            )}
                            limit={items.reduce(
                                (acc, item) => acc + item.limit,
                                0,
                            )}
                        />
                    </h1>
                    <p className="mb-8 text-lg font-normal text-gray-500 sm:px-16 lg:px-48 lg:text-xl dark:text-gray-400">
                        <a
                            href="https://getoutline.org/get-started/#step-3"
                            className="hover:underline"
                        >
                            Download client
                        </a>
                    </p>
                    <div className="mt-8 flex flex-row flex-col justify-center space-y-4">
                        {loaded && items.length < 5 && (
                            <button
                                className="inline-flex items-center justify-center rounded-lg bg-blue-700 px-5 py-3 text-center text-base font-medium text-white hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 dark:focus:ring-blue-900"
                                onClick={handleAdd}
                                disabled={loading}
                            >
                                {loading && (
                                    <svg
                                        aria-hidden="true"
                                        role="status"
                                        className="me-3 inline h-4 w-4 animate-spin text-white"
                                        viewBox="0 0 100 101"
                                        fill="none"
                                        xmlns="http://www.w3.org/2000/svg"
                                    >
                                        <path
                                            d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
                                            fill="#E5E7EB"
                                        />
                                        <path
                                            d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
                                            fill="currentColor"
                                        />
                                    </svg>
                                )}
                                {loading ? 'Generating' : 'Generate'} new key
                            </button>
                        )}
                        {loaded
                            ? items.map(
                                  (outline: OutlineType, index: number) => (
                                      <a
                                          key={index}
                                          href={outline.url}
                                          target="_blank"
                                          rel="noreferrer"
                                          className="flex cursor-pointer justify-between rounded-lg border border-gray-200 bg-white px-5 py-3 text-sm font-medium text-gray-900 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:outline-none focus:ring-4 focus:ring-gray-100 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white dark:focus:ring-gray-700"
                                      >
                                          <div>{outline.name}</div>
                                          <div>
                                              {humanSize(outline.spending)} /{' '}
                                              {humanSize(outline.limit)}
                                          </div>
                                      </a>
                                  ),
                              )
                            : [...Array(5).keys()].map((index: number) => (
                                  <div
                                      key={index}
                                      className="flex cursor-pointer justify-between rounded-lg border border-gray-200 bg-white px-5 py-3 text-sm font-medium text-gray-900 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:outline-none focus:ring-4 focus:ring-gray-100 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white dark:focus:ring-gray-700"
                                  >
                                      <div className="m-1.5 inline-flex h-2 w-28 animate-pulse rounded-full bg-gray-300 dark:bg-gray-600"></div>
                                      <div className="m-1.5 inline-flex h-2 w-20 animate-pulse rounded-full bg-gray-300 dark:bg-gray-600"></div>
                                  </div>
                              ))}
                    </div>
                </div>
            </section>
        </>
    );
};

type LimitsProps = {
    loading: boolean;
    spending: number;
    limit: number;
};

const Limits: FC<LimitsProps> = ({ loading, spending, limit }: LimitsProps) => {
    return (
        <>
            {loading ? (
                <>
                    <div className="mb-1 inline-flex h-4 w-24 animate-pulse rounded-full bg-gray-300 dark:bg-gray-600"></div>{' '}
                    /{' '}
                    <div className="mb-1 inline-flex h-4 w-24 animate-pulse rounded-full bg-gray-300 dark:bg-gray-600"></div>
                </>
            ) : (
                <>
                    {humanSize(spending)} / {humanSize(limit)}
                </>
            )}
        </>
    );
};
