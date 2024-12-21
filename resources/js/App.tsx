import { Outline } from '@/Components/Outline';
import { FC } from 'react';

export const App: FC = () => {
    return (
        <>
            <div className="container mx-auto my-12 bg-white dark:bg-gray-800">
                <Outline />

                <footer className="fixed bottom-0 left-0 z-20 flex w-full items-center justify-center border-t border-gray-200 bg-white p-4 shadow md:p-6 dark:border-gray-600 dark:bg-gray-800">
                    <span className="text-center text-sm text-gray-500 dark:text-gray-400">
                        Made with{' '}
                        {Math.random() > 0.8 ? 'whiskey 🥃' : 'love ❤️'}{' '}
                        by&nbsp;
                        <a
                            className="cursor-pointer hover:underline"
                            onClick={() =>
                                window.Telegram.WebApp.openTelegramLink(
                                    'https://t.me/+Jpvu1a-zH205ZTBi',
                                )
                            }
                        >
                            Finagin
                        </a>
                    </span>
                </footer>
            </div>
        </>
    );
};
