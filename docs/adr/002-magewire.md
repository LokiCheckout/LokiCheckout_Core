# ADR: Dependendency on Magewire v1

This checkout makes use of numerous Magewire components that are communicating via emitted events. Every single field in every single form should be an independent Magewire component. The downside is that every block + component needs to be declared explicitly in the layout. However, the benefit of this is that common issues can be solved easily with basic knowledge of the XML layout (and a bit of Magewire): Adding a new step, swapping the ordering of fields, adding new fields 

### Upgrade to Magewire v3
@todo: Breaking changes
