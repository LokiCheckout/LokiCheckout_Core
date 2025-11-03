# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [2.1.18] - 03 November 2025
### Fixed
- Fix agreements validation if in final step
- Update composer keywords
- Allow for `region_id` in customerData section output
- Add component repository repository so when bulking, country is saved first
- Cleanup postCodes from `window.checkoutConfig`
- Remove postcode validations for countries that are not allowed
- Fix issue with country ID being appended to housenumber after StreetLines refactor
- In shipping preview, use codes if actual method could not be found
- Add optional StepContextInterface

## [2.1.17] - 22 October 2025
### Fixed
- Implement new method createFromStreet with countryId argument
- Make street lines parser only available for configured countries (NL, BE)
- Require countryId for usage of StreetLines
- Allow psr/log 1 and 2 besides 3 as well
- Do not escape `$css()` with `escapeHtmlAttr()` but `escapeHtml()`
- Add LokiComponentExtender for mixins and other modifications

## [2.1.16] - 17 October 2025
### Fixed
- Move LokiHtmlUpdater from LokiCheckout to `Loki_Components`
- Document latest version of template
- Fix newlines after comments
- Copy generic CI/CD files
- Update MODULE.json
- Skip invalid components that are not visible
- Add `loki_checkout_payment_before_save_quote` event for additional information
- When collecting invalid components, explicitly remove components that are gone
- Make change to input optional

## [2.1.15] - 15 October 2025
### Fixed
- Split-up customer section "loki-checkout" in subclasses
- Cleanup image templates with width, height and size
- Improve HTML structure of payment methods
- WIP
- Do not check invalid DOB if still empty
- Set DOB min and max properly

## [2.1.15] - 14 October 2025
### Fixed
- Skip invalid components that are not visible
- Add `loki_checkout_payment_before_save_quote` event for additional information
- When collecting invalid components, explicitly remove components that are gone
- Make change to input optional

## [2.1.14] - 10 October 2025
### Fixed
- Add better styling of agreement pre-tag
- Add translation for "View terms"
- Allow agreement checkbox text to contain magic <a> tag
- Restructure agreement template into smaller templates
- Move Alpine directive templates in specific directive-folder
- Implement new x-tooltip directive
- Failsafe if button-reference is not found (when switching steps fi)
- Add Dutch translations
- Hint to complete address details before selecting shipping address
- Set aria-disabled=true on step-forward-button if not valid
- Add button hint to step-forward button
- Change button color to match WCAG 2.2 AAA
- Fix button color of inactive button
- Apply proper ARIA attributes to tab components
- Move dependency with `Yireo_HyvaThemeAutoRegistration` from core to Hyva-module
- Move `HyvaThemeAutoRegistration` from `LokiCheckout_Core` to `LokiCheckout_Hyva`
- Allow overriding `js_component_name` via XML layout

## [2.1.13] - 09 October 2025
### Fixed
- Reset localStorage of cart and loki-checkout right before finishing everything
- Add my-8 to address forms

## [2.1.12] - 08 October 2025
### Fixed
- Fix styling of agreements
- Turn margins into gaps
- Fix password field styling under Luma

## [2.1.11] - 08 October 2025
### Fixed
- Allow sorting of children via block argument "sort_order"
- Apply flexbox and gap to address and sidebar
- Improve spacing of blocks within steps

## [2.1.10] - 08 October 2025
### Fixed
- Allow changing button classes
- Set fill:white for SVG within btn-primary
- Always enable the step-forward button, just change its appearance
- When there are no shipping methods, mark LokiCheckoutShippingMethods component as valid

## [2.1.9] - 07 October 2025
### Fixed
- Remove redundant width and fill-color from step forward button icons

## [2.1.8] - 07 October 2025
### Fixed
- Refactor address state logic to allow reset after certaing changes, like country change
- When switching country, wipe out street, postcode, city and region
- Split up empty-message of shipping methods in separate template
- HTML content of agreement wrongly escaped
- Add new list-block under neath shipping methods
- Add JS translations
- Allow for JS translations of dates

## [2.1.7] - 06 October 2025
### Fixed
- Show loader when no items
- Move expand buttons for Alpine shipping methods into child-templates
- Remove prevent-modified from buttons
- Option to show all available locations or deliveryDates
- Make parent element of entire location block a label
- Fix hickup in locations and delivery dates with shipping methods in JS
- Fix selection of current shipping method location in Alpine
- Prevent null value to break x-time directive
- Turn expandLocations link into button
- Standardize time slots with start and end property
- Allow x-time to match both 00:00:00 and 00:00
- Make sure to use cart item renderer for product image in sidebar
- Add btn class as default to various buttons
- Refresh shipping methods when changing shipping address postcode, street, region, city or country

## [2.1.6] - 01 October 2025
### Fixed
- Improve image rendering

## [2.1.5] - 30 September 2025
### Fixed
- Add create account button
- Fix issues with payment and shipping methods not being displayed properly
- Do not show gender in sidebar if field is not shown either

## [2.1.4] - 30 September 2025
### Fixed
- Render progress bar with overlay without loader icon
- Do not move messages to top
- Make sure max items to display in sidebar is never 0
- With login-component, only refresh sectionData completely after AJAX is done
- Add x-cloak to conditional loader for shipping method templates
- Fix gitlab being overwritten

## [2.1.3] - 27 September 2025
### Fixed
- Enforce isValid() method in StepViewModels
- Enhance error messaging with Step Navigator
- Fix wrong escaping

## [2.1.2] - 25 September 2025
### Fixed
- Fix wrong escaping

## [2.1.1] - 25 September 2025
### Fixed
- Fix dependency with Map Components
- Move deliveryDates and locations to LokiCheckout Alpine store
- Move generic shipping blocks from MyParcelNl to core

## [2.1.0] - 24 September 2025
### Added
- Allow LokiCheckoutCoreComponentBaseQuoteFieldQuoteFieldViewModel to be used directly
- Implement new imageRenderer
- Implement isValid() method in step ViewModels properly
- Clean up step ViewModel code with new AbstractStepViewModel
- Move new login component to `LokiCheckout_Core` and beautify
- Move sort order of steps from XML layout into `loki_checkout.xml`
- Add custom newsletter component to give local error when checkbox is still disabled
- Add loki-checkout customerData section
- Add window.checkoutConfig to page

### Fixed
- Make only required method of section class public, rest is private
- Remove redundant CSS classes from icon containers
- Rename loki.script from container to block to allow caching
- Change containers into blocks to allow for caching
- Add `.prevent` modifier to `@click` event handler
- Rename Alpine store checkout to LokiCheckout, components to LokiComponents
- Better way to check if checkout session has a quote or not via CheckoutState::hasQuote()
- Prevent empty quote from sending back wrong section data of loki-checkout
- Implement proper validation of previous steps when proceeding to next step
- Reset steps properly when switching theme
- Automatically append target for private data for checkout components
- Add new component for private data syncing after component updates
- Extend loki-checkout section with quote customer details
- Make sure invalid step can not be set
- Make sure only enabled steps are returned by StepNavigator
- Allow steps to have no block yet (outside of checkout)
- Allow for sorting steps via layout argument `sort_order`

## [2.0.26] - 16 September 2025
### Fixed
- Rename shipping.shipping-methods to shipping.methods
- Move page messages to fixed position in Luma
- Add deps

## [2.0.25] - 10 September 2025
### Fixed
- Remove minicart
- Change label of grid_layout to Address Grid Layout
- Add escaping
- Update MODULE.json with PHPCS severity 9
- Reorganize scripts and Alpine.data
- Add PHPStan levels
- Divide shipping JS templates into small templates
- Create generic PHTML template for tab-buttons

## [2.0.24] - 04 September 2025
### Fixed
- Allow overriding propertyName in AdditionalInformationRepository
- Fix integration tests
- Improve logic for detecting existing newsletter subcribers

## [2.0.23] - 03 September 2025
### Fixed
- Make sure to add grid styles to relevant grid block
- PHP 8.1 fix
- Copy generic CI/CD files

## [2.0.22] - 02 September 2025
### Fixed
- Fix bug in disappearing field labels for street1 or higher
- Add StoreView scope to config parameters

## [2.0.21] - 02 September 2025
### Fixed
- Change loki-checkout.payment.payment-methods to loki-checkout.payment.methods
- Cleanup event listener for performance
- Add translations
- Add meta description
- Do not allow HTML in agreements title
- Always have a field label, but just hide it sometimes
- Refactor Loki-library location in Playwright tests

## [2.0.20] - 29 August 2025
### Fixed
- Propertly clean payment method additional data if no longer available
- Enhance check for active payment method with quote arg
- Double-check that saved payment method is indeed available
- Add recurring setup to check for modules to enable

## [2.0.19] - 28 August 2025
### Fixed
- Empty flag for "Save in addressbook" causes PHP type error (sometimes)
- Set a maximum age for dob

## [2.0.18] - 27 August 2025
### Fixed
- Add CI files
- Throw validation error if dob is validated with non-sense value
- Cleanup
- Fix PHP check in Gitlab CI/CD

## [2.0.17] - 26 August 2025
### Fixed
- Additional translations
- Change IBAN validator warning
- Add reusable parent classes for additional payment information components
- Make date of birth configurable
- Add IBAN validator
- Fix jumping to top
- Add GitLab CI files

## [2.0.16] - 21 August 2025
### Fixed
- Jump to top when switching steps
- Disable component-extender logic in XML layout because it is unfinished
- Move scripts from top of body to bottom of body
- Add dependency with loki/magento2-css-utils
- Refactor GridStyleParser to use new LokiCssUtilsUtilCssStyle
- Refactor GridClassParser to use new LokiCssUtilsUtilCssClass
- Re-ordering blocks
- Fix duplicate import
- Replace LokiComponentsUtilBlockCssClass with LokiCssUtilsUtilCssClass
- Fix newlines after comments
- Declare used PHP namespaces
- Add escaping of template code
- Document latest version of template
- Add missing `strict_types` declaration
- Add missing `$css()`

## [2.0.15] - 19 August 2025
### Fixed
- Change heading from h3 to h2 because of SEO-purpose
- Document latest version of template
- Add missing `declare(strict_types)`

## [2.0.14] - 18 August 2025
### Fixed
- Allow for PHP 8.1 compatibility
- Lower requirements to PHP 8.1
- Split up login.phtml into smaller more configurable templates
- Coupon code was always open
- A11Y fixes for coupon code label
- IMP: changed a to button
- Add icons to toggle buttons for cart item
- Fix newlines after comments
- IMP: move all code in to there own file
- IMP: col fallback
- Option to limit cart items in sidebar but collapse to entire list
- Enhance text for config option
- Fix item to be number
- Add admin config for `max_items_visible_count`

## [2.0.13] - 15 August 2025
### Fixed
- Split up login in sidebar into child templates
- Prevent non-grid child blocks from getting grid classes
- Fix issue with billing address being shown even though same-as-billing is checked

## [2.0.12] - 15 August 2025
### Fixed
- Implement new CSS class `loki-col-span` properly
- Set col span via CSS properties instead of too many Tailwind classes
- Convert option for inline CSS styles to CSS properties
- Add new `loki_checkout/cart/max_items_visible_count` option to Config class
- Move `netherlands` grid of `default_grid` to `LokiCheckout_Nl` module
- Formatting

## [2.0.11] - 14 August 2025
### Fixed
- Allow default sort order, col-span and md:col-span to be configured via DI type
- Rename tab in Store Config from "Yireo" to "Loki"
- When step-value in component is empty and there is only 1 step in total, use that step
- Fix re-ordering bug of field blocks after AJAX update

## [2.0.10] - 14 August 2025
### Fixed
- Fix integration tests with ConfigFixture scope
- Draft of subscribing to newsletter by default
- Add config option to subscribe to newsletter by default

## [2.0.9] - 13 August 2025
### Fixed
- Move Tailwind `order-x` classes into custom CSS rules to reduce CSS size
- Add `data-valid` attribute to fields
- Fix potential JS error when coupon code component has no value
- Prevent module without composer.json from causing error in backend module overview
- Update totals when applying discount via coupon code
- Fix escaping in template

## [2.0.8] - 10 August 2025
### Fixed
- Allow toggling loader overlay via showLoader property
- Prevent jump to top

## [2.0.7] - 07 August 2025
### Fixed
- Remove obsolete todo
- Remove obsolete todo
- Allow overriding LokiCheckoutDateOptions via XML layout
- Jump to top when switching steps
- DDo not trigger dispatchPaymentMethodEvent when payment is still empty
- Allow configuration of reactivateTimeout via XML layout

## [2.0.6] - 06 August 2025
### Fixed
- Allow XML layout to set message area to make component messages global or local
- Add before/after container to local messages container
- Make sure agreement child-component is working properly with all step-logic
- Better way to handle step validation and aria-invalid
- Prevent re-post when step-hash is not shown in URL yet
- Set submitted component-flag via step-forward button
- Add submitted flag to all components; Fix step-detection for customer fields
- Turn each agreement into separate Alpine component
- Only render sidebar if HTML not empty
- Lower PHP to 8.2
- Remove unwanted newline after noEscape
- Implement escaping in templates
- Remove legacy code
- Use aria-label instead of title in step-icon

## [2.0.5] - 01 August 2025
### Fixed
- Rewrite progress for easier adjustments like additional links and icons
- Add missing `$css` wrapper
- Finalize new structure of agreements
- Move modal close button into separate PHTML
- Implement modal for agreements; Implement child templates in agreements.phtml
- Make strings translatable
- Add method IconResolverContext::getIconAsset()
- Wrong ViewModel reference leads to Fatal Error
- Remove duplicate child HTML
- Fix broken module name in README
- Add translate attribute to various XML layout arguments

## [2.0.4] - 30 July 2025
### Fixed
- Make errorText of agreements translatable
- Make agreement text and content translatable in PHTML
- Add ARIA labels
- Allow translating step labels
- Enhance translations when adding and removing items
- Additional child renders in sidebar
- Only add payment method code to sidebar in Developer Mode
- Add output HTML element
- Reload step when hash changes
- Rename LokiCheckoutSteps component to LokiCheckoutProgressbar
- Various fixes in GitLab CI config

## [2.0.3] - 28 July 2025
### Fixed
- Support for `checkout/options/max_items_display_count`

## [2.0.2] - 24 July 2025
### Fixed
- Split up `coupon_code.phtml` into child templates
- Enhance toggling of coupon code
- Fix var definition in comments of `login.phtml`

## [2.0.1] - 24 July 2025
### Fixed
- Add `aria-invalid` attribute to agreement checkboxes
- Add `required` attribute to agreement checkboxes 
- Set `h-full` instead of flex utils, to prevent resizing image in picture-tag
- Only resize image to width, not width and height
- Rename `jumpTo` to `jumpToMarker`
- Remove obsolete component scripts

## [2.0.0] - 22 July 2025
### Fixed
- Rename PHP namespace from `Yireo_Loki*` to `Loki*`
- Rename composer package from `yireo/magento2-loki*` to `loki/magento2*`
- Bump loki/magento2-components to major 2.0
- Bump loki/magento2-map-components to 0.1
- Bump loki/magento2-field-components to major 2.0
- Move optional CLI and admin features to separate modules

## [1.0.10] - 17 July 2025
### Fixed
- Move title logic from template to XML layout

## [1.0.9] - 08 July 2025
### Fixed
- Remove escaping from SVG output
- Show `error_text` from layout when agreements are not checked yet
- Add `aria-required` to agreements
- Allow retrieving masked quote ID from CheckoutState
- Allow LokiCheckout prefixed modules to be working with Hyva automatically
- Remove the cart drawer from Hyva Themes

## [1.0.8] - 02 July 2025
### Fixed
- Reset agreements after checkout completion
- Properly disabled proceed button if agreements are still unchecked
- Fix final step error when there is no redirect
- Fix Fatal Error with checkout agreements
- Allow HTML in agreements box
- Fixes for block sorting
- Add order-[] class to grid blocks on load
- Remove deprecated getCurrentGrid method
- Remove newline
- Fix PHPMD issues

## [1.0.7] - 20 June 2025
### Fixed
- Finalize new grid settings
- Properly allow overriding and merging of `loki_checkout.xml`
- Add Dutch translations

## [1.0.6] - 20 June 2025
### Fixed
- Undo validation of current step on load

## [1.0.5] - 20 June 2025
### Fixed
- Disable jumpto for now
- Finish new loki-checkout.xml structure
- Rewrite grid layouts to include sub-entity grid

## [1.0.4] - 18 June 2025
### Fixed
- Add loader to coupon code box
- Fixes for Phpstan level 4
- Add escaping of variables
- Move debug icon to SVG file
- Autocomplete fixes for street, region and city
- Use Loki test-case in Playwright to detect JS errors automatically
- Rewrite @helpers to @loki in Playwright tests
- Draft of ApplePay behaviour
- Make sure success URL is a string
- Enhance workflow of errors during final stage
- PHPUnit behaving strange with skipDispatchToCheckout protected variable
- Remove old deprecated trait
- Remove old commented function

## [1.0.3] - 22 May 2025
### Fixed
- Register `Loki_FieldComponents` as a checkout module
- Easier support for image output
- Add label title of payment method code
- Add assertion for usage in PSP integration tests
- Ignore redirect URLs with no length
- Make sure to wait for beforePostNextStep calls before proceeding to next step
- Add proper support for payment instruction text
- Additional checks for final button and component validation
- Trigger component-update event after selecting payment methtod
- Move remove invisible components to LokiComponents
- Double-check if image exists before returning payment icon
- iImprove mechanism to detect tests
- Remove deprecated `use_required_block` setting (which is in FieldComponents now)
- Fix core issue with postcode 1234 allowed by core
- Double-check integration test for postcode validation
- Add CSS wrapper
- Update admin settings with tooltip and regenerate new MODULE.json
- Add tooltip to config values

## [1.0.2] - 13 May 2025
### Fixed
- Fix possible error
- Only dump active modules to loki-checkout.json
- Make sure to include empty modules as well for module dump
- Refactor mechanism to fetch tests from folders
- Fix additional runs of Playwright tests
- Fix warning when array keys are not matched
- Rewrite all beforeInit and afterInit into new initMethod structure
- Rewrite Alpine from initActions object to methods starting with init
- Allow PHP 8.4 in CI
- Remove lines on ES config
- Fix issue with LokiCheckoutMollie DI type overriding core validators
- Do not make BLOCK constant public
- Rewrite integration test flag $skipDispatchToCheckout
- Mobile fixes
- Fix default md:col-span-x
- Fix detection of Hyva/default-csp theme
- Fix type error when no states are required whatsoever
- Luma layout prefix was depending on Luma/blank not Magento/blank, typo
- Prevent error with only optional regions (@RikWillems)

## [1.0.1] - 28 April 2025
### Fixed
- Apply Rector fixes
- Loosen support for magento/module-catalog
- Remove unused imports
- Remove deprecated classes
- Update composer and module deps

## [1.0.0] - 25 April 2025
### Fixed
- Allow upgrading to LokiFieldComponents and LokiComponents 1.0
- Remove `x-model` because of CSP compliance
- Fix wrong saveQuote call
- Failsave for null values in repositories
- CSP fixes
- Add additional Loki modules to Hyva Theme autoregistration
- Add composer suggest
- Customize backdrop of modal dialog
- Draft of fast checkout option for PSPs
- Consolidate vault access in core module
- Intercept unwanted exceptions while rendering
- Optimize guest checkout flags
- Move from `x-init-data` to separate `text/x-loki-init` script to prevent escaping issues
- Implement core customer address validation
- Move Mollie fixture from core to Mollie module
- Fix possible issue when LokiCheckout config is used outside of checkout
- Add lazyloading to cart image
- Finish grid layout JS switching
- Add LokiCheckoutGrid component
- Add empty grid for later usage
- Remove gridClass, add colSpan and mdColSpan in grid layouts
- Allow switching of grid layout per country ID
- Add new Alpine directives x-time, x-datetime, x-date
- Remove CheckoutSession from context
- Remove FieldTemplateLocator from context
- Remove CartRepository from all contexts
- Rewrite `getCartRepository()->save()` to `getCheckoutState()->saveQuote()`
- Reduce dependencies with CheckoutState::saveQuote()
- Make loader icon in fields depend on Alpine and activate only after configurable timeout
- Rename JS validators/ folder to validator/
- Fix invalid HTML in shipping method input

## [0.1.21] - 16 April 2025
### Fixed
- Always persist chosen customer address ID
- When creating a new address, also reset the `customer_address_id` on load
- Change address toggle label on billing step to "Add new billing address"
- Skip shipping address validation for virtual quotes
- Fix issue with one-line street being saved earlier
- Move image from PostNl to core
- Move common templates from PostNL to core
- Unify locations and addresses for any maps
- Turn login into modal popup
- Add JS password complexity validation
- Move scripts from "before.body.end" to new "loki-scripts" container
- Add configurable password complexity
- Rename LokiCheckoutValidator to LokiComponentValidator
- Add handle prefixes for Hyva, Luma and Breeze
- Implement label for gridlayouts
- Add optional countryId to grid XML
- Add label to grid

## [0.1.20] - 10 April 2025
### Fixed
- Exit after redirecting

## [0.1.19] - 10 April 2025
### Fixed
- Use a normal redirect when quote is dead and AJAX is not there

## [0.1.18] - 10 April 2025
### Fixed
- Move dep with "yireo/magento2-hyva-theme-auto-registration" to Hyva module
- Add yireo:loki-checkout:modules:dump command

## [0.1.17] - 09 April 2025
### Fixed
- Restructure loki classes for Luma
- Fix width definition of icon in step button

## [0.1.16] - 09 April 2025
### Fixed
- Switch from TimezoneInterface to Timezone because of DI arg scopeType issue
- Allow switching shipping details in sidebar to alternate template
- Finalize Post NL templating and abstract Shipping classes
- Add ext/intl requirement
- If street is 1 line, explode by space

## [0.1.15] - 08 April 2025
### Fixed
- Working version of Post NL form with saved values and tab selection
- Simplify loading state of target components
- Remove redundant address-select template
- Remove unneeded important flag
- Remove select-icon when loading select-field
- Add warning if coupon code failed to apply
- Add generic Location, Timeframe and Address classes, fi with Post NL
- Mobile fixes

## [0.1.14] - 04 April 2025
### Fixed
- Remove overlay when reloading StepForwardButton component

## [0.1.13] - 04 April 2025
### Fixed
- StepBackwardButtonViewModel was missing required method

## [0.1.12] - 04 April 2025
### Fixed
- Add Playwright functional tests
- Move to Mollie-specific DI config to Mollie plugin
- Allow for specific exceptions to redirect back to checkout/cart
- Remove specific step button templates in favor of FieldComponents
- Modify overlay when loading next step
- Add icons to step button
- Refactoring of Integration Tests
- Refactor FinalStep to get rid of legacy OneStep code
- Disable autocomplete for coupon code
- Implement better DOM ID for shipments too
- Add better DOM ID for payment methods
- Remove `positive_number` filter from telephone and fax
- Fix issue with duplicate DOM IDs again (but now with test)
- First draft of pickup locations
- Resolving of shipping method icons
- Enhance CSS of method listings
- Refactor PaymentRedirectUrl plugin to resolver
- Refactor PaymentMethodIcon plugins into resolvers
- Enable `hide_region_when_optional` by default
- Implement LokiCheckoutRepositoryTestCase
- Enable GitLab CI/CD
- Add help text depending upon `enable_vat_id_only_for_company`
- Refactor BillingAddress and ShippingAddress components to generic Address components
- Use separate template for billing address form title
- Do not ever copy address if the SameAddress service says no

## [0.1.11] - 11 March 2025
### Fixed
- Fix issue with grid CSS classes
- Add better exception message when Context::getStepProvider() is missing

## [0.1.10] - 11 March 2025
### Fixed
- Fix order completion for virtual orders
- Move GridLayout from XML layout to config
- Remove default grid class
- Implement option `loki_checkout/general/default_grid_class`
- Dynamically change title of shipping address form
- Rename `field_name` to `fieldName`
- Fix client-side validation after refactoring to LokiFieldComponents
- Fix age verification in test
- Fix gender validation in test
- Replace PrefixValidator with more generic OptionsValidator
- Add proper date validation of date of birth
- Fix possible error if somebody is spoofing value
- Make sure gender and DOB are required if set so in config
- Accessibility fixes
- Fix styling of country box when there is an error
- Fix location of buttons
- Move required block
- Add module dependencies
- Huge refactoring to move logic into new LokiFieldComponents
- System Check for module dependencies
- Add test for new ViewModel
- Add button label for quote without payment required
- Add default responsiveness to all fields
- Fix name of defaults block
- Re-enable postcode format validator test
- Refactor and adjust styling
- Refactor SystemCheck architecture in admin
- Check for dev modules not being enabled in Production Mode
- Move EmailValidatorTest
- Add missing Dutch translations
- Move email availability in separate validator `email_available`
- Rename `loki-checkout.css_classes` to `loki-components.css_classes`
- Rewrite Alpine.store() APIs
- Add datetime to module overview

## [0.1.9] - 25 February 2025
### Fixed
- Standardize JS event names
- Rename checkout:payment:method-activate to loki-checkout prefix
- Rename loki-checkout-before-redirect to loki-checkout.before-redirect
- Rename loki:dom-update to loki-components:dom-update
- Rename yireo-loki-checkout.component-change to loki-components.component.update
- Add generic LokiComponentsLogger
- Refactor yireoLokiCheckoutLogger to LokiCheckoutLogger object
- StepForwardButton not activated after component updates (use $nextTick)

## [0.1.8] - 24 February 2025
### Fixed
- Rerun LokiCheckoutStepsShippingForwardButton.updateValid() at every update

## [0.1.7] - 24 February 2025
### Fixed
- Hide payment method component by default
- Fix non-existing method NewsletterRepository::getDefaultValue()
- Change Place Order label when virtual quote
- Add missing Dutch translation

## [0.1.6] - 24 February 2025
### Added
- Save in Address Book checkbox
- Add more translation strings

### Fixed
- Prevent null from throwing TypeError / Beautify shipping method label
- Allow saving more variable types
- Call to undefined method getDefaultValue()
- Wrong exception caught
- Prevent saving non-existing payment method still set as default

## [0.1.5] - 24 February 2025
### Fixed
- Prevent exception when selecting shipping method repeatedly
- Allow default value in field to be saved automatically
- Retrieve option model from ViewModel
- Remove empty option for gender
- Remove bgcolor from login card
- Add Dutch translations
- Remove demo field label "Surname"

## [0.1.4] - 23 February 2025
### Fixed
- Intercept exception with addShippingRate

## [0.1.3] - 23 February 2025
### Fixed
- Remove unneeded collectTotals
- Cleanup totals calculation
- Cleanup cart totals rendering
- Failsafe to skip loading shipping methods if country ID is empty
- Make sure default country ID is saved to quote
- Prevent endless loop when submitting to AJAX which then fails
- Simplify shipping method calculation
- Recalculate totals properly via AJAX
- Fix shipping methods test except totals
- Re-enable default shipping method pick
- Dont render address template when HTML is empty anyway
- Add doc link to module overview
- Carrier label not found if carrier no longer active

## [0.1.2] - 22 February 2025
### Fixed
- Update loki_checkout.xml grid CSS for mobile

## [0.1.1] - 22 February 2025
- Small responsive fixes
- Show no billing step title if quote is virtual
- Simplify customer fields block in onestep theme
- Allow customer fields to be shown in either billing or shipping step
- Hide shipping address view and shipping methods for virtual quote
- Hide same-as-billing for virtual quotes
- Empty address was still showing country
- Disable Same as Shipping if quote is virtual
- Allow hiding billing title via XML layout
- Default payment method was not saved to quote
- Lift requirements

## [0.1.0] - 21 February 2025
- Version bump

## [0.0.7] - 21 February 2025
### Added
- New admin page for Module Status
- New admin page for System Checks
- Add reusable RegionProvider

### Fixed
- Set disabled CSS for select as well
- Fix issue with HTML attributes for nested components
- Fix JS numeric validator
- Simplify Agreements handling
- Add DI plugin as workaround for non-working `free_shipping_subtotal`
- Improve streetlines logic with faulty values
- Append streetline values if streetlines config is wrong
- Add "not available" text to unavailable shipping methods
- Add address tag to shipping address preview
- Fix newsletter subscribe for logged in customers
- Rewrite newsletter subscribe logic
- Rename console message from INFO to DEBUG

### Removed
- Remove deprecated `{component_name}` variable for HTML attribute

### Refactor
- Better handling of streetlines
- Rename plugin classes to include `Plugin` suffix
- Refactor LocalMessageRegistry methods
- Refactor `text_select` to avoid unneeded ID suffix

## [0.0.6] - 13 February 2025
### Fixed
- Fix address when customer is logged in and has no addresses to select yet
- Prefix options are separated by semicolumn, not column
- Properly switch shipping method after initial saving
- Fix issue in refreshing other targets
- Fix forms without proper name
- Enhance payment method icon logic
- Redirect to cart when AJAX fails with empty quote
- Load default theme if theme is messed up
- Add proper UnknownThemeException

### Refactor
- Refactor Validator to remove dependency with component
- Huge refactor to move context dependencies in the open
- Move CssClass to LokiComponents

## [0.0.5] - 21 January 2025
- Initial public release

## [0.0.4] - 13 January 2025
- Reach MVP level

## [0.0.3] - 19 December 2024
- Move from Magewire to Loki Components

## [0.0.2] - 2 October 2024
- Copy from GitHub repository to GitLab repository
- Rename to FriggaCheckout and then LokiCheckout

## [0.0.1] - 11 July 2024
- Initial proof-of-concept (MagewireCheckout)
