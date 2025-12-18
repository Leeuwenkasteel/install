# Install

Instaleren van het Leeuwenkasteel install package om andere packages te instaleren.

## Vereisten
- PHP ^8.1
- Composer
- MySQL / MariaDB

## Installatie

1. Clone de repository
```
git clone https://github.com/Leeuwenkasteel/install.git
```

2. Voeg de Service provider toe aan bootstrap/providers.php
```
Leeuwenkasteel\Install\InstallPackageServiceProvider::class,
```

3. Voeg toe aan composer.json:
```
"require": {
    "livewire/livewire": "^3.6"
},
"require-dev": {
    "leeuwenkasteel/install": "^1.0"
},
"repositories": [
    {
        "type": "path",
        "url": "packages/leeuwenkasteel/install",
        "options": {
            "symlink": true
        }
    }
]
```
4. Voer in een cmd het volgende command uit:
   ```
   composer dumpautoload
   composer update
    ```
6. ga naar:
```
https::url.nl/install
```







