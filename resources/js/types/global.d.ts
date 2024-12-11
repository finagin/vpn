import { AxiosInstance } from 'axios';
import { route as ziggyRoute } from 'ziggy-js';

declare global {
    interface Window {
        axios: AxiosInstance;
        Telegram: {
            WebApp: {
                initData: string;
                close: () => void;
                openLink: (url: string) => void;
                openTelegramLink: (url: string) => void;
            };
        };
    }

    /* eslint-disable no-var */
    var route: typeof ziggyRoute;
}
