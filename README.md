# UMC
Magento 2 Ultimate Module Creator

## Description  
This is a standalone application for creating Magento 2 CRUD modules.  
This application is intended for developers. It should not be used in production.  
Also, the modules you create with it should be tested before moved to production.

<strong>You like the work the developer has done and it saved you a lot o time and money, consider <a href="https://www.paypal.me/MariusStrajeru/10">donating</a>. Any amount is welcomed. Just change 10 from the previous URL.</strong>

## Compatibility  
The modules you create with this application should be compatible with Magento 2.1.* and 2.2.* versions.  
They might work with 2.3.*, but 2.3 is not officially supported yet.   

## Requirements  
PHP version 7.1.3 or higher. Some PHP extension. See composer.json for more details.

## Installation   
 - create a folder on your server
 - navigate to that folder
 - clone this repo: `https://github.com/UltimateModuleCreator/umc.git .`
 - run `composer install`
 - copy `.env.dist` to `.env`
 - go to that folder in your browser.
 
## How to use  
 - In the top menu named, surprisingly, "Menu", click on the item "Create Module"
 - Fill in the form as you want. Each field has a tooltip explaining what it is used for.
 - Add as many entities and attributes / fields as you need.
 - See also the FAQ section if something is unclear.
 - You can also select one of the modules you previously created and edit it.
 - After you are done, you can find a zip file with your extension in `var/umc` folder. Unzip that over your magento instance.
 
## FAQs  
 - **If I edit a module, and copy the files in my magento instance over the previous version, does it work?**  
    Possibly, but most probably not. If you changed the structure of the entities you will need to uninstall manually the module and install it again.
 - **I'm adding some default values for some fields but they don't reflect in my extension. Why?**  
    The select and multiselect attributes are generated using those fancy select boxes, with search and "stuff". For some reason, this is a Magento bug/limitation. They don't accept default values. via ui-component xml.  
 - **I want to add my module admin menu inside one of my custom admin menu iteams. How do I do that?**  
    You can edit the `config/services.yaml` file, in the section `menu_type_config` and add your menu item(s) similar to the others.  
    Or, you can edit the `config/form/module.yml` file and make the menu dropdown be a text field where you can fill in your desided value. For `menu_parent` field change the attribute `type` from `menu` to `text`
                        
## Coding Standards  
The generated extensions follow the magento coding standards, but...  
Due to the values filled in the form, you can easily break one of the rules. Special the max 120 chars per line.   
This is not prevented by the UMC, but you will find in the arvhive that is generated with the module 3 files. All pf them start with `PHPCS_`. ((these files are not useful for the proper fuctioning of the extension you create))    
These files contain the report after running php code sniffer on the generated code with the PSR2, PSR12 and the EcgM2 standards.  
Keep in mind that not everything that is in there is actually an error. False positives will occur. For example, the PSR standrds will report that you are using underscores to prefix method names. This is because you need methods like `_beforeSave` to make magento work.   
Also, the EcgM2 standard will report that ther eare methods that start with `before` or `after` that are not public. This is because ECGM2 things these are plugins. Ignore them   
If you have your own coding standards you can add them the `config/services.yaml` file as part of the `standards` argument of the `App\Util\CodingStandardsFactory` class

## Contributing to the UMC    
 - If you find a bug, report it <a href="https://github.com/UltimateModuleCreator/umc/issues">here</a>  
 - you have a cool idea for improving it but you don't want to implement it, post it <a href="https://github.com/UltimateModuleCreator/umc/issues">here</a>. There are no guarantees that it will get implemeted though.  
 - you have a cool idea for improving it and you can implement it, feel free to make a PR. But before doing so, make sure that the new code you create it is covered with unit tests and the existing unit tests still pass.  
 - You think the UI looks ugly and you want to change it? I think so too, but I lack the skills. I would really apreciate an UI if someone wants to get involved.

  
