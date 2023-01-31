import dayjs from "dayjs";
import React, { useState } from "react";
import Dropdown from "../Dropdown";
function classNames(...classes) {
  return classes.filter(Boolean).join(" ");
}

// This is a custom filter UI for selecting
// a unique option from a list
export function SelectColumnFilter({
  column: { filterValue, setFilter, preFilteredRows, id, render },
}) {
  // Calculate the options for filtering
  // using the preFilteredRows
  const options = React.useMemo(() => {
    const options = new Set();
    preFilteredRows.forEach((row) => {
      options.add(row.values[id]);
    });
    return [...options.values()];
  }, [id, preFilteredRows]);

  // Render a multi-select box
  return (
    <label className="flex gap-x-2 items-baseline">
      <span className="text-gray-700">{render("Header")}: </span>
      <select
        className="rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
        name={id}
        id={id}
        value={filterValue}
        onChange={(e) => {
          setFilter(e.target.value || undefined);
        }}
      >
        <option value="">All</option>
        {options.map((option, i) => (
          <option key={i} value={option}>
            {option}
          </option>
        ))}
      </select>
    </label>
  );
}

export function StatusPill({ value }) {
  const status = value || "unknown";
  const colorize = ["active", "inactive", "offline", "super-admin", "admin"];

  return (
    <>
      {Array.isArray(status) ? (
        status.map((item) => (
          <>
            <span
              className={classNames(
                "px-3 py-1 uppercase leading-wide font-bold text-xs rounded-full shadow-sm",
                item.startsWith("active")
                  ? "bg-green-100 text-green-800"
                  : null,
                item.startsWith("inactive")
                  ? "bg-yellow-100 text-yellow-800"
                  : null,
                item.startsWith("offline") ? "bg-red-100 text-red-800" : null,
                item.startsWith("super-admin")
                  ? "bg-amber-400 text-amber-900"
                  : null,
                item.startsWith("admin")
                  ? "bg-emerald-400 text-emerald-900"
                  : null,
                colorize.indexOf(item) < 0
                  ? "bg-slate-400 text-slate-100"
                  : null
              )}
            >
              {item}
            </span>
            <br></br>
          </>
        ))
      ) : (
        <span
          className={classNames(
            "px-3 py-1 uppercase leading-wide font-bold text-xs rounded-full shadow-sm",
            status.startsWith("active") ? "bg-green-100 text-green-800" : null,
            status.startsWith("inactive")
              ? "bg-yellow-100 text-yellow-800"
              : null,
            status.startsWith("offline") ? "bg-red-100 text-red-800" : null,
            status.startsWith("super-admin")
              ? "bg-amber-400 text-amber-900"
              : null,
            status.startsWith("admin")
              ? "bg-emerald-400 text-emerald-900"
              : null,
            colorize.indexOf(status) < 0 ? "bg-slate-400 text-slate-100" : null
          )}
        >
          {status}
        </span>
      )}
    </>
  );
}

export function DateCell({ value, column, row }) {
  const date = value ? dayjs(value) : "unknown";

  if (!date === "unknown" && !date.isValid()) {
    return "";
  }

  return (
    <>
      {date.format("DD/MM/YYYY")}
      <br></br>
      {column.includeTime ? date.format("HH:mm:ss") : ""}
    </>
  );
}

export function AvatarCell({ value, column, row }) {
  const [isInView, setIsInView] = useState(false);

  return (
    <div className="flex items-center">
      <div className="flex-shrink-0 h-10 w-10">
        {isInView ? (
          <div className="bg-cover rounded-md">
            <img
              className="h-10 w-10 rounded-full"
              src={row.original[column.imgAccessor]}
              alt=""
              onError={() => setIsInView(false)}
            />
          </div>
        ) : (
          <div
            className={`h-10 w-10 bg-red-400 shadow-md flex justify-center items-center rounded-full`}
          >
            <p className={`text-white font-bold text-sm`}>A</p>
          </div>
        )}
      </div>
      <div className="ml-4">
        <div className="text-sm font-medium text-gray-900">{value}</div>
        <div className="text-sm text-gray-500">
          {row.original[column.emailAccessor]}
        </div>
      </div>
    </div>
  );
}

export function ActionCell({ value, column, row }) {
  return (
    <div>
      <Dropdown>
        <Dropdown.Trigger>
          <span className="inline-flex rounded-md">
            <button
              type="button"
              className="inline-flex items-center px-4 py-2 bg-gray-900 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest active:bg-gray-900 active:opacity-50 transition ease-in-out duration-150"
            >
              Action
            </button>
          </span>
        </Dropdown.Trigger>

        {column.customOptions ? (
          column.customOptions(value, column, row)
        ) : (
          <Dropdown.Content>
            <Dropdown.Link
              href={`${route(column.routeView, { id: value })}`}
              method="get"
              as="button"
            >
              View
            </Dropdown.Link>
            <Dropdown.Link
              href={`${route(column.routeEdit, { id: value })}`}
              method="get"
              as="button"
            >
              Edit
            </Dropdown.Link>
            <Dropdown.Button
              onClick={() => {
                column.showDelete(row.original);
              }}
            >
              Delete
            </Dropdown.Button>
          </Dropdown.Content>
        )}
      </Dropdown>
    </div>
  );
}
export function TrashedActionCell({ value, column, row }) {
  return (
    <div>
      <Dropdown>
        <Dropdown.Trigger>
          <span className="inline-flex rounded-md">
            <button
              type="button"
              className="inline-flex items-center px-4 py-2 bg-gray-900 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest active:bg-gray-900 active:opacity-50 transition ease-in-out duration-150"
            >
              Action
            </button>
          </span>
        </Dropdown.Trigger>

        {column.customOptions ? (
          column.customOptions(value, column, row)
        ) : (
          <Dropdown.Content>
            <Dropdown.Link
              href={`${route(column.routeView, { id: value })}`}
              method="get"
              as="button"
            >
              View
            </Dropdown.Link>
            <Dropdown.Button
              onClick={() => {
                column.showRestore(row.original);
              }}
            >
              Restore
            </Dropdown.Button>
            <Dropdown.Button
              onClick={() => {
                column.showDelete(row.original);
              }}
            >
              Permanent Delete
            </Dropdown.Button>
          </Dropdown.Content>
        )}
      </Dropdown>
    </div>
  );
}
