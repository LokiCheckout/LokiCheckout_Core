module.exports = {
    safelist: [
        {
            pattern: /col-span-\d/
        },
        {
            pattern: /order-\d/
        }
    ],
    content: [
        "../../../etc/loki_checkout.xml",
        "../layout/*.xml",
        "../templates/**/*.phtml"
    ],
};
