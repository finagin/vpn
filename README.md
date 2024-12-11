<!-- START doctoc generated TOC please keep comment here to allow auto update -->
<!-- DON'T EDIT THIS SECTION, INSTEAD RE-RUN doctoc TO UPDATE -->

- [Локальная разработка](#%D0%9B%D0%BE%D0%BA%D0%B0%D0%BB%D1%8C%D0%BD%D0%B0%D1%8F-%D1%80%D0%B0%D0%B7%D1%80%D0%B0%D0%B1%D0%BE%D1%82%D0%BA%D0%B0)
  - [Laravel Sail](#laravel-sail)

<!-- END doctoc generated TOC please keep comment here to allow auto update -->

## Локальная разработка

### Laravel Sail

Установка зависимостей для использования Sail:
```shell
docker compose run --rm init
```

Переменные окружения:
```shell
cp .env.example .env
```

Сборка и запуск контейнеров:
```shell
vendor/bin/sail up -d --build --force-recreate --remove-orphans
```

Для удобства с использованием Sail можно создать алиас:
```shell
alias sail='sh $([ -f sail ] && echo sail || echo vendor/bin/sail)'
```
