import React, { useState } from "react";
import { useStateContext } from "@/Contexts/ContextProvider";
import { MenuIcon, BellIcon, XIcon } from "@heroicons/react/outline";
import ApplicationLogo from "@/Components/ApplicationLogo";
import AvatarMenu from "@/Components/AvatarMenu";

const Header = ({ user, component }) => {
  const { appState, setAppState, saveState, media } = useStateContext();
  const [alwaysHide, setAlwaysHide] = useState(appState.screenSize < media.lg);

  return (
    <header className="bg-gray-800 fixed top-0 left-0 h-16 w-full z-40">
      <div className="px-4 lg:px-6">
        <div className="flex h-16 items-center justify-between">
          <div className="flex items-center">
            <div className="flex z-10 md:mr-2">
              {/* Mobile menu button */}
              <button
                className="inline-flex items-center justify-center rounded-md bg-gray-800 p-2 text-gray-400 hover:bg-gray-700 hover:text-white focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800"
                onClick={() => {
                  setAppState((prevState) => {
                    const state = {
                      ...prevState,
                      activeSidebar: !prevState.activeSidebar,
                    };
                    if (!alwaysHide) {
                      saveState({ activeSidebar: !prevState.activeSidebar });
                    }
                    return state;
                  });
                }}
              >
                <span className="sr-only">Open main menu</span>
                <MenuIcon className="block h-6 w-6" aria-hidden="true" />
              </button>
            </div>
            <div className="hidden md:flex md:flex-shrink-0">
              <ApplicationLogo className="h-8 w-8 fill-white" />
            </div>
          </div>
          <div className="absolute left-0 w-full md:static md:-ml-2 md:w-auto flex items-center">
            <ApplicationLogo className="block h-8 w-auto mx-auto md:hidden fill-white" />
          </div>
          <div className="block">
            <div className="ml-4 flex items-center md:ml-6">
              <button
                type="button"
                className="rounded-full bg-gray-800 p-1 text-gray-400 hover:text-white focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800"
              >
                <span className="sr-only">View notifications</span>
                <BellIcon className="h-6 w-6" aria-hidden="true" />
              </button>

              <AvatarMenu
                name={user.name}
                email={user.email}
                role={user.type}
                profilePhoto={user.profile_photo_url}
              />
            </div>
          </div>
        </div>
      </div>
    </header>
  );
};

export default Header;
