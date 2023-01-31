import { useRemember } from "@inertiajs/inertia-react";
import React, { createContext, useContext, useState } from "react";

const StateContext = createContext();

export const ContextProvider = ({ children }) => {
  const initState = localStorage?.getItem("appState")
    ? JSON.parse(localStorage.getItem("appState"))
    : {};

  const media = {
    sm: 640,
    md: 768,
    lg: 1024,
    xl: 1280,
    xxl: 1536,
  };

  const screenSizeMedia = (screenSize) => {
    let result = "sm";
    for (const key of Object.entries(media)) {
      if (media[key] <= screenSize) {
        result = media[key];
        break;
      }
    }

    return result;
  };

  const [appState, setAppState] = useRemember(
    {
      activeSidebar: initState.activeSidebar === true ? true : false,
      screenSize: initState.screenSize,
    },
    "appState"
  );

  const [isContentOverflow, setIsContentOverflow] = useState(false);

  const saveState = (state) => {
    const dataState = { ...initState, ...state };
    localStorage.setItem("appState", JSON.stringify(dataState));
  };

  return (
    // eslint-disable-next-line react/jsx-no-constructed-context-values
    <StateContext.Provider
      value={{
        initState,
        appState,
        setAppState,
        isContentOverflow,
        setIsContentOverflow,
        saveState,
        screenSizeMedia,
        media
      }}
    >
      {children}
    </StateContext.Provider>
  );
};

export const useStateContext = () => useContext(StateContext);
