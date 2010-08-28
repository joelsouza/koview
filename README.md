# View module for KO3

## What does it do ?

It provides multiple render engines to be used when creating Views.

Supported engines:

* PHP (Kohana render)
* JSON
* CSV (still in progress)
* XML/RSS (still in progress)
* Haml
* Sass
* Smarty
* Mustache
* Dwoo
* Less (still in progress)

## TODO

Still needs a lot of code clean up.

A big thanks to all the porters of renders and basic idea from MrAnchovy's Smarty module.

Do you know of any renders you want to see here ? Email me [birkir dot gudjonsson at gmail dot com]


## Benefits

Build it so it will be easy to add more engines.

No changes needed, just simple as:

<code>$view = new View('haml:path/to/template');</code>

or for RSS / JSON or other data feed:

<code>$view = new View('xml:', array('data to feed'));</code>
