# node-sass-bundle

libsass Assetic filter

## Installation

Install `node-sass` via npm (not covered)

Add to `composer.json`:

```
  "the-island/node-sass": "*",
  ```
  
Add to `AppKernel.php`:

```
  new TheIsland\NodeSassBundle\TheIslandNodeSassBundle()
```

Add to`config.yml`:

```
the_island_node_sass:
    bin: /usr/local/bin/node-sass
    style: "nested"
    debug: false
    load_paths: [%kernel.root_dir%/../bower_components]
    apply_to: "^(?<!_).+\.scss$"
```

* **bin** - path to a node-sass executable
* **style** - see `--output-style` in `node-sass -h`
* **debug** - turns on `--source-comments`
* **load_paths** - adds paths to `--include-path`
* **node** - path to your local node (might help if node is not on your path)
 
