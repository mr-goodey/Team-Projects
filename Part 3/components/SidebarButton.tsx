import { Menu } from "lucide-react";
import $ from "jquery";

const SidebarButton = (props: any) => {
  const handleOpenSidebar = () => {
    let input = $("#my-drawer-2");
    input.prop("checked", true);
  };
  return (
    <div
      className="px-4 py-3 flex h-fit hover:bg-base-200 rounded cursor-pointer"
      onClick={handleOpenSidebar}
    >
      <Menu size={24} />
    </div>
  );
};

export default SidebarButton;
