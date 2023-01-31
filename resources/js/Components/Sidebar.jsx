import { useStateContext } from "@/Contexts/ContextProvider";
import { Link, usePage } from "@inertiajs/inertia-react";
import React, { useEffect, useState } from "react";
import { classNames } from "@/Helper/Utils";
import DynamicIconOutline from "./DynamicIconOutline";

const Sidebar = ({ navigation = [] }) => {
  const { initState, appState, setAppState, saveState, media } =
    useStateContext();
  const { component } = usePage();

  function dropDownToggle(event, key) {
    const listNav = document.querySelector(
      `#side-nav-menu-${key.toLowerCase()}`
    );
    const iconNav = document.querySelector(
      `#side-nav-menu-icon-${key.toLowerCase()}`
    );

    if (listNav) listNav.classList.toggle("hidden");
    if (iconNav) iconNav.classList.toggle("rotate-180");
    if (iconNav) iconNav.classList.toggle("rotate-0");
  }

  function navLink(item, showIcon = true) {
    if (Array.isArray(item.route)) {
      return (
        <>
          <button
            href="#"
            onClick={(e) => dropDownToggle(e, item.label)}
            className={classNames(
              component.startsWith(item.component)
                ? "bg-gray-800"
                : "bg-gray-900",
              "w-full flex justify-between rounded-md text-slate-200 hover:bg-gray-700 px-2 py-2 mb-2"
            )}
          >
            <div className="inline-flex">
              {showIcon && (
                <DynamicIconOutline
                  iconName={item.icon}
                  className="h-6 w-6 stroke-slate-200 mr-3"
                />
              )}
              <label className="cursor-pointer">{item.label}</label>
            </div>
            <div
              id={`side-nav-menu-icon-${item.label.toLowerCase()}`}
              className="duration-300 rotate-0"
            >
              <DynamicIconOutline
                iconName="ChevronDownIcon"
                className="h-4 w-4 stroke-slate-200"
              />
            </div>
          </button>
          <ul
            id={`side-nav-menu-${item.label.toLowerCase()}`}
            className="w-full pl-9 hidden"
          >
            {item.route.map((itm, index) => {
              return (
                <li key={`side-nav-${itm.label.toLowerCase()}`}>
                  {Array.isArray(itm.route)
                    ? navLink(itm, true)
                    : navLink(itm, false)}
                </li>
              );
            })}
          </ul>
        </>
      );
    } else {
      return (
        <Link
          onClick={() => {
            console.log("screenMedia", appState.screenMedia);
            console.log("screenSize", appState.screenSize);
            if (appState.screenMedia === "sm" || appState.screenMedia == "md") {
              setAppState((prevState) => {
                const state = {
                  ...prevState,
                  activeSidebar: !prevState.activeSidebar,
                };
                return state;
              });
            }
          }}
          href={item.route}
          className={classNames(
            component.startsWith(item.component)
              ? "bg-gray-800"
              : "bg-gray-900",
            "w-full flex rounded-md text-slate-200 hover:bg-gray-700 px-2 py-2 mb-2"
          )}
          as="button"
        >
          {showIcon && (
            <DynamicIconOutline
              iconName={item.icon}
              className="h-6 w-6 stroke-slate-200 mr-3"
            />
          )}
          <label className="cursor-pointer">{item.label}</label>
        </Link>
      );
    }
  }

  useEffect(() => {
    const handleResize = () =>
      setAppState((prevState) => {
        let state = {
          ...prevState,
          screenSize: window.innerWidth,
        };
        if (window.innerWidth < media.lg) {
          state = { ...state, activeSidebar: false };
        } else {
          state = { ...state, activeSidebar: initState.activeSidebar };
        }
        saveState({
          screenSize: window.innerWidth,
        });
        return state;
      });
    window.addEventListener("resize", handleResize);

    handleResize();
    return () => window.removeEventListener("resize", handleResize);
  }, []);

  return (
    <aside
      className={`flex ${
        appState.activeSidebar ? "translate-x-0" : "-translate-x-full"
      } bg-gray-900 pt-24 pb-16 px-4 lg:px-6 fixed ${
        appState.screenSize < media.lg ? "w-full z-10" : "w-72"
      } text-white h-screen overflow-y-auto select-none flex-auto ease-in-out duration-300`}
    >
      <ul className="w-full">
        {navigation.map((item) => (
          <li key={`side-nav-${item.label.toLowerCase()}`}>{navLink(item)}</li>
        ))}
      </ul>
    </aside>
  );
};

export default Sidebar;
