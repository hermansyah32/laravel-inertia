import React, { useState, useContext, Fragment, useEffect } from "react";
import { Link } from "@inertiajs/inertia-react";
import { Menu, Transition } from "@headlessui/react";
import MenuSchema from "@/Helper/MenuSchema";

function classes(...classes) {
    return classes.filter(Boolean).join(" ");
}

/**
 * Render menu button
 * @param {MenuSchema} menu Menu button schema.
 */
const renderMenuButton = (menu) => {
    switch (menu.type) {
        case "custom":
            return <Menu.Button>{menu.content}</Menu.Button>;

        default:
            // return all to link type
            return (
                <Menu.Button>
                    <Link
                        className={classes(
                            menu.isActive ? menu.activeClass : menu.hoverClass,
                            menu.className
                        )}
                        href={menu.href}
                    >
                        {menu.label}
                    </Link>
                </Menu.Button>
            );
    }
};

/**
 * Render child menu
 * @param {MenuSchema} menu Child menu schema.
 * @param {Number} key Key name
 */
const renderChildMenu = (menu, key) => {
    switch (menu.type) {
        case "separator":
            return <div className="px-1 py-1" key={key}></div>;

        default:
            // return all to link type
            return (
                <Menu.Item as={Fragment} key={key}>
                    <Link
                        className={classes(
                            menu.isActive ? menu.activeClass : menu.hoverClass,
                            menu.className
                        )}
                        href={menu.href}
                    >
                        {menu.label}
                    </Link>
                </Menu.Item>
            );
    }
};

/**
 * Set default class for each menu button
 * @param {MenuSchema} menus Menu button schema
 */
const setMenuButtonDefaultClass = (menu) => {
    menu.activeClass = "bg-gray-900 text-white";
    menu.hoverClass = "text-gray-300 hover:bg-gray-700 hover:text-white";
    menu.className = "px-3 py-2 rounded-md text-sm font-medium";
};

/**
 * Set default class for each child menu item
 * @param {MenuSchema[]} menus Child menu schema
 */
const setChildMenuDefaultClass = (menus) => {
    menus.forEach((menu) => {
        if (!menu.activeClass) menu.activeClass = "bg-gray-200";
        if (!menu.hoverClass) menu.hoverClass = "hover:bg-gray-100";
        if (!menu.className)
            menu.className = "block px-4 py-2 text-sm text-gray-700";
    });
};

export default function NavMenu({
    menu,
    childMenus,
    className = "",
    key = "",
}) {
    const [open, setOpen] = useState(false);

    useEffect(() => {
        console.log("open :>> ", open);
        setMenuButtonDefaultClass(menu);
        setChildMenuDefaultClass(childMenus);
    }, []);

    return (
        <Menu
            as="div"
            className={className}
            key={key}
            onMouseEnter={() => setOpen(true)}
            onMouseLeave={() => setOpen(false)}
        >
            <Menu.Button>
                <span className="sr-only">Open {menu.label} menu</span>
                <Link
                    className={classes(
                        menu.isActive ? menu.activeClass : menu.hoverClass,
                        menu.className
                    )}
                    href={menu.href}
                >
                    {menu.label}
                </Link>
            </Menu.Button>
            {childMenus.length && (
                <Transition
                    as={Fragment}
                    show={open}
                    enter="transition ease-out duration-100"
                    enterFrom="transform opacity-0 scale-95"
                    enterTo="transform opacity-100 scale-100"
                    leave="transition ease-in duration-75"
                    leaveFrom="transform opacity-100 scale-100"
                    leaveTo="transform opacity-0 scale-95"
                >
                    <Menu.Items className="absolute left-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
                        {childMenus.map((childMenu, childKey) => {
                            return renderChildMenu(
                                childMenu,
                                key + "-child-menu-" + childKey
                            );
                        })}
                    </Menu.Items>
                </Transition>
            )}
        </Menu>
    );
}
