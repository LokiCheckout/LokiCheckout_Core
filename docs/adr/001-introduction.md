# ADR: Introduction to this checkout

To be blunt, this checkout is inspired by the Hyvä Checkout: That checkout uses Magewire, offers a smoother developer experience than any Luma-based or React-based checkout so far. However, it has its caveats too: Swapping the billing and the shipping step is difficult, because things are too hard-coded. Also, the entire Hyvä Checkout forms one big Magewire component, making it more difficult to add Magewire behaviour to just one single field.

This checkout aims to use Magewire to build an alternative solution, with most of the downsides of the Hyvä Checkout fixed. During development, no code of the Hyvä Checkout is being used, except for the HTML and JS as outputted in an official Hyvä Checkout demo site.

