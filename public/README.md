# Gestion du Cache avec Apache (.htaccess)

Pour optimiser la performance de chargement de vos pages web, il est recommandé de configurer Apache pour mettre en cache les fichiers statiques tels que les CSS, JavaScript et les images. Cela peut être accompli en utilisant un fichier .htaccess dans le répertoire public de votre application Symfony.

## Configuration du Cache

Assurez-vous que votre fichier `.htaccess` contient les directives appropriées pour spécifier la durée de mise en cache des différents types de fichiers. Voici un exemple de configuration :

```apache
    <IfModule mod_expires.c>
        ExpiresActive On
        ExpiresByType text/css "access plus 1 year"
        ExpiresByType application/javascript "access plus 1 year"
        ExpiresByType image/png "access plus 3 months"
        ExpiresByType image/jpeg "access plus 3 months"
        ExpiresByType image/gif "access plus 3 months"
    </IfModule>
```
