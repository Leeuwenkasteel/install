# Install

Instaleren van het Leeuwenkasteel install package om andere packages te instaleren.

## Vereisten
- PHP ^8.1
- Composer
- MySQL / MariaDB

## Installatie

1. Clone de repository
```bash
git clone https://github.com/Leeuwenkasteel/install.git
```

2. Voeg de Service provider toe aan bootstrap/providers.php
```bash
Leeuwenkasteel\Install\InstallPackageServiceProvider::class,
```

3. Voeg toe aan composer.json:
   require:
   ```bash
"livewire/livewire": "^3.6"
```

   require-dev:
   ```bash
"leeuwenkasteel/install": "^1.0"
```

En voeg onder aan de pagina toe:
  ```bash
"repositories": [
        {
            "type": "path",
            "url": "packages/leeuwenkasteel/install",
            "options": {
                "symlink": true
            }
        },
]
```



