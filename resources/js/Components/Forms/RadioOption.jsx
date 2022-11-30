import React from "react";

export default function RadioOption({ name, value, handleChange }) {
  return (
    <input
      type="radio"
      name={name}
      value={value}
      className="rounded border-gray-300 text-red-600 shadow-sm focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50"
      onChange={(e) => handleChange(e)}
    />
  );
}
