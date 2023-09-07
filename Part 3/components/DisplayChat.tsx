
import { FC, useEffect, useState } from "react";
import TextInput from "./TextInput";

//static - need to change it so that it opens depending on what user chat is clicked

type Chat = {
  chat: {
    id: number;
    name: string;
    messages: [
      {
        text: string;
        timestamp: string;
        user: {
          username: string;
          id: number;
        };
      }
    ];
  };
};

type Messages =
  | [
      {
        text: string;
        timestamp: string;
        user: {
          username: string;
          id: number;
        };
      }
    ]
  | [];

type DisplayChatProps = {
  chat: Chat | null;
  userId: number;
  textInputStatus: boolean;
};

const DisplayChat: FC<DisplayChatProps> = (props) => {
  const [messages, setMessages] = useState<Messages>([]);
  useEffect(() => {
    if (props.chat && messages.length == 0) {
      setMessages(props.chat.chat.messages);
    }
  }, []);

  return (
    <>
      <div className="flex flex-col justify-between min-h-screen">
        <div className="flex-grow p-5">
          {messages
            ? messages.map((message, index) => (
                <div
                  className={`chat ${
                    props.userId == message.user.id ? "chat-end" : "chat-start"
                  }`}
                  key={index}
                >
                  {props.userId != message.user.id && (
                    <div className="chat-header opacity-60 mb-0.5">{message.user.username}</div>
                  )}
                  <div
                    className={`chat-bubble ${
                      props.userId == message.user.id
                        ? "chat-bubble-primary"
                        : "chat-bubble-accent"
                    }`}
                  >
                    {message.text}
                  </div>
                  <div className="chat-footer opacity-50 my-1">
                    {formatDate(message.timestamp)}
                  </div>
                </div>
              ))
            : null}
        </div>
        <div className="mt-auto p-10">
          <TextInput
            textInputStatus={props.textInputStatus}
            userId={props.userId}
            chatId={props.chat ? props.chat.chat.id : null}
            setMessages={setMessages}
            messages={messages}
          />
        </div>
      </div>
    </>
  );
};

const formatDate = (timestamp: string): string => {
  const date = new Date(timestamp);
  const today = new Date();

  const isToday =
    date.getDate() === today.getDate() &&
    date.getMonth() === today.getMonth() &&
    date.getFullYear() === today.getFullYear();

  if (isToday) {
    // Format for today
    const hours = String(date.getHours()).padStart(2, "0");
    const minutes = String(date.getMinutes()).padStart(2, "0");
    return `${hours}:${minutes}`;
  } else {
    // Format for other dates
    const day = String(date.getDate()).padStart(2, "0");
    const month = String(date.getMonth() + 1).padStart(2, "0");
    const year = String(date.getFullYear()).slice(2);
    const hours = String(date.getHours()).padStart(2, "0");
    const minutes = String(date.getMinutes()).padStart(2, "0");
    return `${day}/${month}/${year} - ${hours}:${minutes}`;
  }
};

export default DisplayChat;
