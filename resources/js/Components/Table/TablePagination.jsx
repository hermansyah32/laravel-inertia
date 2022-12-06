import {
  ChevronDoubleLeftIcon,
  ChevronDoubleRightIcon,
  ChevronLeftIcon,
  ChevronRightIcon,
} from "@heroicons/react/outline";
import React from "react";
import { PageButton, TableButton } from "./Button";

function TablePagination({
  canPreviousPage,
  canNextPage,
  previousPage,
  nextPage,
  gotoPage,
  pageIndex,
  pageSize,
  pageCount,
  setPageSize,
  pageOptions,
}) {
  const listOfPage = [];

  if (pageCount) {
    for (let index = 0; index < pageCount; index++) {
      listOfPage.push(index);
    }
  }

  return (
    <>
      {pageCount ? (
        <div className="py-3 flex items-center justify-between">
          <div className="flex-1 flex justify-between sm:hidden">
            <TableButton
              onClick={() => previousPage()}
              disabled={!canPreviousPage}
            >
              Previous
            </TableButton>
            <TableButton onClick={() => nextPage()} disabled={!canNextPage}>
              Next
            </TableButton>
          </div>
          <div className="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div className="flex gap-x-2 items-baseline">
              <span className="text-sm text-gray-700">
                Page
                <select
                  className="font-medium rounded-md mx-1 border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                  defaultValue={pageIndex}
                  onChange={(e) => {
                    gotoPage(Number(e.target.value));
                  }}
                >
                  {listOfPage.map((page) => (
                    <option key={page} value={page}>
                      {page + 1}
                    </option>
                  ))}
                </select>
                of <span className="font-medium">{pageOptions.length}</span>
              </span>
              <label>
                <span className="sr-only">Items Per Page</span>
                <select
                  className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                  defaultValue={pageSize}
                  onChange={(e) => {
                    setPageSize(Number(e.target.value));
                  }}
                >
                  {[15, 30, 50].map((pageSize) => (
                    <option key={pageSize} value={pageSize}>
                      Show {pageSize}
                    </option>
                  ))}
                </select>
              </label>
            </div>
            <div>
              <nav
                className="relative z-0 inline-flex rounded-md shadow-sm -space-x-px"
                aria-label="Pagination"
              >
                <PageButton
                  className="rounded-l-md"
                  onClick={() => gotoPage(0)}
                  disabled={!canPreviousPage}
                >
                  <span className="sr-only">First</span>
                  <ChevronDoubleLeftIcon
                    className="h-5 w-5 text-gray-400"
                    aria-hidden="true"
                  />
                </PageButton>
                <PageButton
                  onClick={() => previousPage()}
                  disabled={!canPreviousPage}
                >
                  <span className="sr-only">Previous</span>
                  <ChevronLeftIcon
                    className="h-5 w-5 text-gray-400"
                    aria-hidden="true"
                  />
                </PageButton>
                <PageButton onClick={() => nextPage()} disabled={!canNextPage}>
                  <span className="sr-only">Next</span>
                  <ChevronRightIcon
                    className="h-5 w-5 text-gray-400"
                    aria-hidden="true"
                  />
                </PageButton>
                <PageButton
                  className="rounded-r-md"
                  onClick={() => gotoPage(pageCount - 1)}
                  disabled={!canNextPage}
                >
                  <span className="sr-only">Last</span>
                  <ChevronDoubleRightIcon
                    className="h-5 w-5 text-gray-400"
                    aria-hidden="true"
                  />
                </PageButton>
              </nav>
            </div>
          </div>
        </div>
      ) : (
        <></>
      )}
    </>
  );
}

export default TablePagination;
