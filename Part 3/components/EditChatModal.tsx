import { useEffect, useState } from "react";
import Cookies from "universal-cookie";
import axios from "axios";
import { useRouter } from "next/router";
import { User } from "lucide-react";

const EditChatModal = (props: any) => {
  const [chatName, setChatName] = useState("");
  const [chatDescription, setChatDescription] = useState("");
  const [chatUsers, setChatUsers] = useState([]);
  const cookies = new Cookies();
  const router = useRouter();
  useEffect(() => {
    if (props.chat) {
      setChatName(props.chat.chat.name);
      setChatDescription(props.chat.chat.description);
      setChatUsers(props.chat.chat.users);
    }
  }, [props.modal]);

  const saveChat = async () => {
    const payload = {
      name: chatName,
      description: chatDescription,
    };
    const res = await axios.put(`/api/chats/${props.chat.chat.id}`, payload);
    props.setModal(false);
    router.push(`/chat/${props.chat.chat.id}`);
  };

  const handleDelete = async () => {
    const res = await axios.delete(`/api/chats/${props.chat.chat.id}`);
    props.setModal(false);
    router.push("/chat");
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
          <div className="flex flex-col gap-4">
            <input
              type="text"
              placeholder="Chat Name"
              className="input input-bordered w-full"
              value={chatName}
              onChange={(e) => setChatName(e.target.value)}
            />
            <textarea
              className="textarea textarea-bordered w-full"
              placeholder="Description"
              value={chatDescription || ""}
              onChange={(e) => setChatDescription(e.target.value)}
            ></textarea>
            <div className="flex gap-4 flex-wrap">
              {chatUsers.map((user: any) => {
                return (
                  <div
                    key={user.user.id}
                    className="flex flex-shrink-0 items-center gap-1 bg-accent text-base-100 rounded py-1 px-2"
                  >
                    <User size={20} />
                    <span>{user.user.username}</span>
                  </div>
                );
              })}
            </div>
          </div>
          <div className="modal-action">
            <button
              id="createChatBtn"
              className="btn btn-primary h-4"
              onClick={saveChat}
            >
              Save
            </button>
            <button
              id="cancelBtn"
              className="btn h-4 btn-secondary"
              onClick={() => props.setModal(false)}
            >
              Cancel
            </button>
            <button className="btn h-4 btn-error" onClick={handleDelete}>
              Delete
            </button>
          </div>
        </div>
      </div>
    </>
  );
};

export default EditChatModal;
