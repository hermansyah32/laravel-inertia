import React from "react";
import { useStateContext } from "@/Contexts/ContextProvider";
import Notification from "./Notification/Notification";
import Footer from "./Footer";

const Content = ({ children, flash, component }) => {
  const { appState } = useStateContext();

  return (
    <div
      className={`flex-1 flex-col ${
        appState.screenSize > 900 && appState.activeSidebar ? "ml-72" : "ml-0"
      } transform duration-300`}
    >
      <div className="flex flex-col h-screen pt-16 overflow-y-auto overflow-x-hidden">
        <Notification flash={flash} />
        <main className="flex-grow mb-6 w-full">{children}</main>
        <Footer className="py-2 px-4 bg-white w-full" />
      </div>
    </div>
  );
};

export default Content;
