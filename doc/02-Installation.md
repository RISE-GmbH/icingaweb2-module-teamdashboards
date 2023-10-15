# Installation <a id="module-teamboards-installation"></a>

## Requirements <a id="module-teamboards-installation-requirements"></a>

* Icinga Web 2 (&gt;= 2.11.4)
* PHP (&gt;= 7.3)

## Installation from .tar.gz <a id="module-teamboards-installation-manual"></a>

Download the latest version and extract it to a folder named `teamboards`
in one of your Icinga Web 2 module path directories.

## Enable the newly installed module <a id="module-teamboards-installation-enable"></a>

Enable the `teamboards` module either on the CLI by running

```sh
icingacli module enable teamboards
```

Or go to your Icinga Web 2 frontend, choose `Configuration` -&gt; `Modules`, chose the `teamboards` module and `enable` it.

It might afterwards be necessary to refresh your web browser to be sure that
newly provided styling is loaded.