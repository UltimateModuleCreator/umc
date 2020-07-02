# UMC
Ultimate Module Creator

## Description  
Ultimate module creator (UMC) is a standalone application for creating CRUD modules / bundles /  for different CMS
You can use the UI to create your entities just like you would use PhpMyAdmin to create tables.  
When you are done, you can download immediately the module from the popup that appears, or you can find your module in var/umc/{platform}/{version} folder.  
This application is intended for developers. It should not be used in production.  
Also, the modules you create with it should be tested before moved to production.  
<strong>You like the work the developer has done and it saved you a lot o time and money, consider <a href="https://www.paypal.me/MariusStrajeru/10">donating</a>. Any amount is welcomed. Just change 10 from the previous URL.</strong>

## Requirements  
PHP version 7.2.5 or higher. Some PHP extensions may be needed. See composer.json for more details. (a docker version may come later)

## Supported platforms
 - Magento 2.3.4+ 
 - Magento 2.4.*
 
## Planned platforms
 - Shopware 6
 - Sylius

## Installation   
 - create a folder on your server
 - navigate to that folder
 - clone this repo: `https://github.com/UltimateModuleCreator/umc.git .`
 - run `composer install`
 - copy `.env.dist` to `.env`
 - go to that folder in your browser.
 
## How to use  
 - From the top left menu, select "All Platforms." and you should see he list of supported platforms.
 - From the "Create Module" column for your desired platform, select the desired version
 - Fill in the form as you want. Each field has a tooltip explaining what it is used for.
 - Add as many entities and attributes / fields as you need.
 - See also the <a href="#faq">FAQ</a> section if something is unclear.
 - You can also select one of the modules you previously created and edit it.
 - After you are done, you can find a zip file with your extension in <code>var/umc/{platform}/{version}</code> folder.

## FAQs  
- If I edit a module, and copy the files in my platform instance over the previous version, does it work? Possibly, but most probably not. If you changed the structure of the entities you may need to uninstall manually the module and install it again.
                        
## Coding Standards  
The generated extensions follow the PSR1, PSR2, PSR12 coding standards, but...<br/>
Due to the values filled in the form, you can easily break one of the rules. Special the max 120 chars per line.  <br />
This is not prevented by the UMC, but you will find in the archive that is generated with the module 3 (or 4) files. All of them are located in the `_phpcs` folder. (these files are not useful for the proper functioning of the module you create)<br/>
These files contain the report after running php code sniffer on the generated code with the PSR1, PSR2, PSR12 and the magento coding standards. (for magento only) <br />
If you have your own coding standards you can add them the <code>platform.yml</code> file of the desired platform under the <code>config/coding_standards</code> section.

## Contributing to the UMC    
 - If you find a bug, report it <a href="https://github.com/UltimateModuleCreator/umc/issues">here</a>  
 - you have a cool idea for improving it but you don't want to implement it, post it <a href="https://github.com/UltimateModuleCreator/umc/issues">here</a>. There are no guarantees that it will get implemeted though.  
 - you have a cool idea for improving it and you can implement it, feel free to make a PR. But before doing so, make sure that the new code you create it is covered with unit tests and the existing unit tests still pass.  

  
