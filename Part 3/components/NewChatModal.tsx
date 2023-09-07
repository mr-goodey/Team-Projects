import { useEffect, useState } from "react";
import { X } from "lucide-react";
import axios from "axios";
import { useRouter } from "next/router";
import Cookies from "universal-cookie";

type Employee = {
  id: number;
  username: string;
  email: string;
};

const NewChatButton = (props: any): JSX.Element => {
  const [employeeSearch, setEmployeeSearch] = useState("");
  const [suggestedEmployees, setSuggestedEmployees] = useState<Employee[]>([]);
  const [selectedEmployees, setSelectedEmployees] = useState<Employee[]>([]);
  const router = useRouter();
  const cookies = new Cookies();
  useEffect(() => {
    if (employeeSearch.length > 1) {
      const res = axios
        .get(
          `/api/users/search/${employeeSearch}`
        )
        .then((res) => {
          let tempSuggestedEmployees = res.data.data;
          tempSuggestedEmployees = tempSuggestedEmployees.filter(
            (employee: any) => employee.id !== props.userId
          );
          setSuggestedEmployees(tempSuggestedEmployees);
        });
    } else {
      setSuggestedEmployees([]);
    }
  }, [employeeSearch]);

  const handleEmployeeSelect = (employee: any) => {
    const alreadySelected = selectedEmployees.find(
      (selectedEmployee) => selectedEmployee.id === employee.id
    );
    if (!alreadySelected) {
      setSelectedEmployees([...selectedEmployees, employee]);
    }
    setEmployeeSearch("");
    setSuggestedEmployees([]);
  };

  const handleCreateChat = async () => {
    let users = [...selectedEmployees];
    const payload = {
      token: cookies.get("token"),
      users: users,
      userId: props.userId,
    };
    const res = await axios.post(
      `/api/chats`,
      payload
    );
    props.setModal(false);
    router.push(`/chat/${res.data.data.id}`);
  };

  return (
    <>
      <div
        className={
          props.modal
            ? "modal modal-open modal-bottom sm:modal-middle"
            : "hidden"
        }
      >
        <div className="modal-box flex flex-col space-y-2 justify-between bg-base-100">
          <div className="relative">
            <input
              type="text"
              id="employee-search"
              placeholder="Search employees..."
              className="input input-bordered w-full"
              onChange={(e) => {
                setEmployeeSearch(e.target.value);
              }}
              value={employeeSearch}
            />
            <div
              className={`relative border-x border-b rounded-b bg-base-100 -mt-2 overflow-y-auto${
                suggestedEmployees.length > 0 ? "" : " hidden"
              }`}
            >
              {suggestedEmployees.map((employee: any) => {
                if (employee.id !== props.userId) {
                  return (
                    <div
                      className="flex justify-between bg-base-100 hover:bg-base-300 rounded p-3 m-2"
                      key={employee.id}
                      onClick={() => handleEmployeeSelect(employee)}
                    >
                      <span>{employee.username}</span>
                      <span className="text-accent">{employee.email}</span>
                    </div>
                  );
                }
              })}
            </div>
          </div>
          <div>
            <div id="addedEmployees" className="flex flex-row flex-wrap mt-2">
              {selectedEmployees.map((employee) => {
                return (
                  <div
                    className="flex items-center bg-accent text-neutral rounded p-2 mr-2 mb-2 cursor-pointer hover:brightness-110"
                    key={employee.id}
                    onClick={() => {
                      setSelectedEmployees(
                        selectedEmployees.filter((e) => e.id !== employee.id)
                      );
                    }}
                  >
                    <span className="mr-1">{employee.username}</span>
                    <X size={18} />
                  </div>
                );
              })}
            </div>
            <div className="modal-action">
              <button id="createChatBtn" className="btn btn-primary h-4" onClick={handleCreateChat}>
                Create chat
              </button>
              <button
                id="cancelBtn"
                className="btn h-4 btn-secondary"
                onClick={() => props.setModal(false)}
              >
                Cancel
              </button>
            </div>
          </div>
        </div>
      </div>
    </>
  );
};

export default NewChatButton;
