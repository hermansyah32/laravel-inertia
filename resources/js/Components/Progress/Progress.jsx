import React, { useState } from "react";

const showText = (progress, withText) => {
  if (!withText) return "";

  return isNaN(progress) ? progress : `${progress}%`;
};

const Progress = ({
  value,
  maxValue,
  color,
  withText = false,
  type = "bar",
}) => {
  const [progress, setProgress] = useState(
    Math.round((value / maxValue) * 100) / 100 > 100
      ? 100
      : Math.round((value / maxValue) * 100) / 100
  );

  return (
    <div className="w-full">
      <div className={`shadow w-full`}>
        <div
          className="bg-black text-xs leading-none py-1 text-center text-white"
          style={{ width: `${isNaN(progress) ? 100 : progress}%` }}
        >
          {showText(progress, withText)}
        </div>
      </div>
    </div>
  );
};

export default Progress;
