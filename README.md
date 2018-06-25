# Snowdog CMS API

The module provides endpoints to get CMS blocks and pages filtered.

### 1. Installation:

`composer require snowdog/module-cms-api`

### 2. Available endpoints: 

* `/rest/V1/snowdog/cmsPage/:pageId`: retrieves page info by its id (integer value)
* `/rest/V1/snowdog/cmsPage/search`: retrieves the list of pages (accepts search criteria filters)
* `/rest/V1/snowdog/cmsBlock/:blockId`: retrieves block info by its id (integer value)
* `/rest/V1/snowdog/cmsBlock/search`: retrieves the list of blocks (accepts search criteria filters)
