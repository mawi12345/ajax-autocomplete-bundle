AjaxAutocompleteBundle - easy jquery ui autocomplete for symfony2
===============================================


## Installation

### Add the following line to your  `composer.json` file:

```
"require": {
	...
	"mawi12345/ajax-autocomplete-bundle": "dev-master",
}
```

You also have to install [FOSJsRoutingBundle](https://github.com/FriendsOfSymfony/FOSJsRoutingBundle).

### Add AjaxAutocompleteBundle to your application kernel
```
    // app/AppKernel.php
    public function registerBundles()
    {
        return array(
            // ...
            new Mawi\AjaxAutocompleteBundle\AMAjaxAutocompleteBundle(),
            // ...
        );
    }
```

### Import routes

// app/config/routing.yml

```
mawi_ajaxautocomplete:
    resource: "@MawiAjaxAutocompleteBundle/Resources/config/routing.xml"
```

### Update your configuration

#### Add form theming to twig
```
twig:
    ...
    form:
        resources:
            - MawiAjaxAutocompleteBundle::fields.html.twig
```
#### Add autocomplete config
```
mawi_ajax_autocomplete:
    autocomplete:
        person:
            class: AMTestBundle:Person
            label: searchLabel
            labelClass: searchLabelClass
            search: contains
            query: "SELECT p FROM AMTestBundle:Person p WHERE p.lastName LIKE :term OR CONCAT(p.lastName, CONCAT(' ', p.firstName)) LIKE :term ORDER BY p.lastName"
            max: 20
        company:
            class: AMTestBundle:Company
            label: name
            search: contains
            query: "SELECT c FROM AMTestBundle:Company c WHERE c.name LIKE :term"
            max: 20
```

### Load jQuery in your views
```
    <script src="http://code.jquery.com/jquery-1.8.2.min.js" type="text/javascript"></script>
```
### Use the FormType
```
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
			->add('title')
            ->add('firstName')
            ->add('lastName')
            ->add('company', 'mawi_ajax_autocomplete', array('entity_alias'=>'company'))
```

