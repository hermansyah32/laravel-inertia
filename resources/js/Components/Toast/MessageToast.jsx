import { XIcon } from "@heroicons/react/outline";
import React, { useEffect, useState } from "react";
import DynamicIconSolid from "../DynamicIconSolid";
import Progress from "../Progress/Progress";

let progressInterval = null;
const MessageToast = ({ title, message, bgColor, icon, duration = 2000 }) => {
  const [progress, setProgress] = useState(0);
  const [open, setOpen] = useState(true);

  useEffect(() => {
    progressInterval = setInterval(() => {
      setProgress((prevProgress) => prevProgress + 10);
    }, 10);
  }, []);

  useEffect(() => {
    if (progress >= duration) {
      clearInterval(progressInterval);
      setOpen(false);
    }
  }, [progress]);

  return (
    <div
      className={`${
        open ? "block" : "hidden"
      } border-l-8 ${bgColor} border-gray-400 fixed mx-4 top-20 left-0 md:left-auto right-0 md:right-2 bg-white rounded-md shadow-lg`}
    >
      <div className={`flex flex-row items-center px-4 py-2 md:w-80`}>
        <DynamicIconSolid iconName={icon} className="h-8 w-8" />
        <div className="block mx-4">
          {title}
          <br></br>
          {message}
        </div>
      </div>
      <div className="w-full">
        <div className={`shadow w-full`}>
          <div
            className="bg-slate-400 text-xs py-0.5 leading-none text-center text-white"
            style={{
              width: `${
                progress / duration >= 100
                  ? 100
                  : Math.round((progress / duration) * 100)
              }%`,
            }}
          />
        </div>
      </div>
    </div>
  );
};

export default MessageToast;
