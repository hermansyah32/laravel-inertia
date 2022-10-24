export default class MenuSchema {
    constructor({
        type,
        label,
        href = "#",
        content = null,
        isActive = false,
        className = null,
        hoverClass = null,
        activeClass = null,
    }) {
        this.type = type;
        this.label = label;
        this.href = href;
        this.content = content || null;
        this.isActive = isActive || false;
        this.className = className || null;
        this.hoverClass = hoverClass || null;
        this.activeClass = activeClass || null;
    }

    static parseFromObject = (object) => {
        const menu = new MenuSchema({
            type: object.type,
            label: object.label,
            href: object.href,
            content: object.content,
            isActive: object.isActive,
        });
        const childMenus = object.items.map(
            (item) =>
                new MenuSchema({
                    type: item.type,
                    label: item.label,
                    href: item.href,
                    content: item.content,
                    isActive: item.isActive,
                })
        );

        return { menu: menu, childMenus: childMenus };
    };

    static parseFromJsontring = (string) => {
        return parseFromObject(JSON.parse(string));
    };
}
