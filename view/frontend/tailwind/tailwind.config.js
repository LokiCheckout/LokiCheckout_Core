module.exports = {
    safelist: [
        {
            pattern: /col-span-\d/
        },
        {
            pattern: /order-\d/
        },
        ...[...Array(200).keys()].flatMap(i => [`order-[${i}]`])
    ],
    theme: {
        extend: {
            order: {

            }
        }
    },
    content: [
        "../../../etc/loki_checkout.xml",
        "../layout/*.xml",
        "../templates/**/*.phtml",
    ],
};
