import Outline from '@/Components/Outline';

export default function App() {
    return (
        <>
            <div className="tg-bg-second container mx-auto">
                <div className="flex h-screen flex-col">
                    <div className="flex flex-1">
                        <div className="flex-1 p-4">
                            <Outline />
                        </div>
                    </div>
                    <div className="flex h-16 items-center justify-center bg-gray-900 text-white">
                        Made with whiskey 🥃 by&nbsp;
                        <a
                            onClick={() =>
                                window.Telegram.WebApp.openTelegramLink(
                                    'https://t.me/+Jpvu1a-zH205ZTBi',
                                )
                            }
                        >
                            Finagin
                        </a>
                    </div>
                </div>
            </div>
        </>
    );
}
