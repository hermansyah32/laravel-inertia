export const navigation = [
  {
    label: "Dashboard",
    icon: "PresentationChartLineIcon",
    url: route("dashboard"),
    show: true,
  },
  {
    label: "Apps",
    url: route("apps"),
    icon: "PuzzleIcon",
    show: true,
  },
  {
    label: "Settings",
    url: route("settings"),
    icon: "CogIcon",
    show: true,
  },
  {
    label: "Account",
    url: route("profile"),
    icon: "UserCircleIcon",
    show: false,
  },
];

export function getNavigation(label) {
  const key = label.split('/')[0];
  const temp = navigation.filter((item) => item.label === key);
  return temp[0] || navigation[0];
}
