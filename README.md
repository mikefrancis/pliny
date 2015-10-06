#Pliny

** This is very much a work in progress and probably just a technical exercise. **

Another PHP static site generator, based on Ruby's [Jekyll](http://jekyllrb.com).

Transform [Markdown](http://daringfireball.net/projects/markdown/) files and [Twig](http://twig.sensiolabs.org) templates into a flat HTML site.

## Usage

### Directory Structure

```
.
├── _config.yml
├── _layouts
|   ├── default.html
|   └── post.html
├── _posts
|   ├── lorem-ipsum.md
|   └── sit-dolor-amet.md
├── _projects
|   └── my-awesome-project.md
├── _site
└── index.html
```

### Build

```
php bin/pliny build
```
