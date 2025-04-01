const {Field, FieldManager} = require(process.cwd() + '/helpers/field');
const {saveCheckoutConfig} = require(process.cwd() + '/helpers/checkout-config');
const {test, expect} = require(process.cwd() + '/fixtures/checkout-page');

test.describe.configure({mode: 'serial'});

test.describe('Generic checkout test', function()  {
  test('should allow me to go to the checkout', async function({checkoutPage, request})  {

    await saveCheckoutConfig(request, {
      config: {
        'customer/address/middlename_show': 1
      }
    });

    await checkoutPage.reload();

    /*
    const availableFields = [
      new Field(checkoutPage, 'customer.email')
    ];

    const fieldManager = new FieldManager(checkoutPage, availableFields);
    fieldManager.fill({
      'customer.email': 'jane@example.com',
    });
    */

    const fieldManager = new FieldManager(checkoutPage);
    const field = fieldManager.getField('customer.email').getLocator();
    field.fill('jane@example.com');
    await expect(field).toHaveValue('jane@example.com');

    return;
    /*
                {id: 'firstname', group: 'shipping', value: 'Jane'},
        {id: 'lastname', group: 'shipping', value: 'Doe'},
        {id: 'company', group: 'shipping', value: 'Yireo'},
        {id: 'city', group: 'shipping', value: 'Baarn'},
        {id: 'street0', group: 'shipping', value: 'Amalialaan'},
        {id: 'street1', group: 'shipping', value: '126'},
        {id: 'street2', group: 'shipping', value: 'D'},
        {id: 'phone', group: 'shipping', value: '0123456789'},
        {id: 'fax', group: 'shipping', value: '0123456789'},
        {id: 'vat-id', group: 'shipping', value: 'NL1234123412'},
        {id: 'shipping-as-billing', group: 'billing', value: '1'},
     */

    /*
    const regionField = fieldManager.getField('region', 'shipping');
    const countryField = fieldManager.getField('country_id', 'shipping');

    fieldManager.fill(countryField, 'US', 'select');
    await expect(regionField).toBeEditable({ timeout: 10000 });
    await expect(regionField).toHaveRole('combobox');
    fieldManager.fill(regionField, '12', 'select');

    fieldManager.fill(countryField, 'NL', 'select');
    await expect(regionField).toBeEditable({ timeout: 10000 });
    await expect(regionField).toHaveRole('textbox');
    fieldManager.fill(regionField, 'Utrecht', 'text');

    const postcodeField = fieldManager.getField('postcode', 'shipping');
    fieldManager.fill(postcodeField, '1111aa', 'text');
    await expect(postcodeField).toHaveValue('1111AA');*/
  });
});
