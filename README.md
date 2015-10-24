# Facebook for Craft CMS

A simple plugin to connect to Facebook's API.

-------------------------------------------

## Requirements

- [Craft](http://buildwithcraft.com/) 2.4+
- [OAuth](https://dukt.net/craft/oauth) plugin for Craft

## Installation

1. Download the latest release of the plugin
2. Drop the `facebook` plugin folder to `craft/plugins`
3. Install Facebook from the control panel in `Settings > Plugins`

## Templating

    {% set response = craft.facebook.api.get('/me/accounts') %}

    {% if response.success %}

        {# Success #}
        <pre>{{ dump(response) }}</pre>

    {% else %}

        {# Error #}
        <pre>{{ response.exception }}</pre>

    {% endif %}

## API

### FacebookVariable

- api() : Returns Facebook_ApiService

### Facebook_ApiService

- get($uri = null, $query = null)

[Dukt.net](https://dukt.net/) © 2015 - All rights reserved