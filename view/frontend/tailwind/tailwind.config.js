module.exports = {
    purge: {
        content: ["../layout/*.xml", "../templates/**/*.phtml"],
    },
    safelist: [
        {
            pattern: /col-span-(1|2|3|4|5|6|7|8|9|10|11|12)/,
        },
    ],
};
