# Changelog

Toutes les modifications notables de ce projet sont documentees dans ce fichier.

Le format est base sur [Keep a Changelog](https://keepachangelog.com/fr/1.1.0/),
et ce projet suit le [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [2.0.2] - 2026-05-19

### Corrige
- Animation slide out au clic sur le bouton de retour en haut.

## [2.0.1] - 2026-05-19

### Corrige
- Cadre focus visible sur le bouton scroll top (accessibilite clavier).
- Chargement du CSS frontend et initialisation au DOM ready (bouton invisible dans certains contextes).
- Texte "Scroll to top" visible dans le bouton.

### Modifie
- Remplacement de `jquery.scrollUp.js` par un script vanilla JS (suppression de la dependance jQuery cote front).
- Menage des fichiers inutiles et refonte du README.

## [2.0.0] - 2026

### Ajoute
- Toggle d'activation / desactivation sans desactiver le plugin.
- Preview live dans l'admin pour visualiser les changements en temps reel.
- Options avancees : distance d'apparition, vitesse de remontee, breakpoint mobile, animation Fade ou Slide.
- Accessibilite : focus visible, attribut `title`, compatibilite aria.
- Filtre PHP `dc_scroll_top_show` pour controle programmatique de l'affichage.

### Modifie
- Refonte du design de la page d'administration, alignee sur DC Plus (Breadcrumb Manager).

## [1.0.0]

### Ajoute
- Premiere version stable publique.

## [0.3.17]

### Ajoute
- Regroupement des menus dans l'administration.

## [0.3.14] - [0.3.13]

### Corrige
- Corrections multiples du mecanisme de mise a jour automatique via GitHub.

## [0.3] - [0.2]

### Ajoute
- Selecteur d'icone et color picker dans l'administration.
- Personnalisation du style (couleur, position, taille).
- Mise a jour automatique du plugin via GitHub.

## [0.1]

### Ajoute
- Premier commit : bouton scroll to top basique.

[2.0.2]: https://github.com/dynamiccreative/dc-scroll-top/releases/tag/v2.0.2
[2.0.1]: https://github.com/dynamiccreative/dc-scroll-top/releases/tag/v2.0.1
[2.0.0]: https://github.com/dynamiccreative/dc-scroll-top/releases/tag/v2.0.0
[1.0.0]: https://github.com/dynamiccreative/dc-scroll-top/releases/tag/v1.0.0
[0.3.17]: https://github.com/dynamiccreative/dc-scroll-top/releases/tag/v0.3.17
[0.3.14]: https://github.com/dynamiccreative/dc-scroll-top/releases/tag/v0.3.14
[0.3.13]: https://github.com/dynamiccreative/dc-scroll-top/releases/tag/v0.3.13
[0.3]: https://github.com/dynamiccreative/dc-scroll-top/releases/tag/v0.3
[0.2]: https://github.com/dynamiccreative/dc-scroll-top/releases/tag/v0.2
[0.1]: https://github.com/dynamiccreative/dc-scroll-top
