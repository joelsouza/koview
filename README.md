# View module for KO3

## What does it do ?

It provides multiple render engines to be used when creating Views.

Supported engines:

* PHP
* JSON
* XML / RSS
* Atom
* CSV
* Haml
* Smarty
* Mustache
* Dwoo

## Benefit

Build it so it will be easy to add more engines.

No changes needed, just simple as:

<code>$view = new View('haml:path/to/template');</code>

or for RSS / JSON or other data feed:

<code>$view = new View('xml:path/to/rss', array('data to feed'));</code>
