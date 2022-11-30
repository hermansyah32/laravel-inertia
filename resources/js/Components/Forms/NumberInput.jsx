import Cleave from "cleave.js/react";
import React, { useEffect, useRef } from "react";

const getCleaveOptions = (type) => {
  let options = {
    numericOnly: true,
  };

  if (type === "phone") {
    options = {
      delimiter: ' ',
      blocks: [3, 4, 6],
      numericOnly: true
    };
  }

  return options;
};

export default function NumberInput({
  type = "integer",
  value,
  className,
  isFocused,
  handleChange,
  disabled = false,
}) {
  const input = useRef();

  useEffect(() => {
    if (isFocused) {
      input.current.focus();
    }
  }, []);

  return (
    <div className="flex flex-col items-start w-full">
      <Cleave
        className={
          `border-gray-300 focus:border-red7-300 focus:ring focus:ring-red7-200 focus:ring-opacity-50 rounded-md shadow-sm ` +
          className
        }
        options={getCleaveOptions(type)}
        value={value}
        onChange={handleChange}
        disabled={disabled}
      />
    </div>
  );
}
