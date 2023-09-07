import jwt from "jsonwebtoken";
import { useRouter } from "next/router";
import { FC, useEffect, useState } from "react";
import $ from "jquery";
import DisplayChat from "@/components/DisplayChat";
import ViewChats from "@/components/ViewChats";
import Sidebar from "@/components/Sidebar";
import SidebarButton from "@/components/SidebarButton";
import Head from "next/head";
import axios from "axios";
import { useCookies } from "react-cookie";
type Chats = {
  chats: [
    {
      chat: {
        id: number;
        name: string;
        description: string;
        users: [
          {
            user: {
              id: number;
              username: string;
            };
          }
        ];
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
    }
  ];
};
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

type ChatProps = {
  chat: Chat;
  chats: Chats;
  chatId: number;
};

const Chat: FC<ChatProps> = (props): JSX.Element => {
  const [userId, setUserId] = useState(0);
  const [cookies, setCookie] = useCookies(["token"]);
  const router = useRouter();
  useEffect(() => {
    const token = String(cookies.token);
    try {
      // json parse and stringify to please typescript
      let decoded = JSON.parse(
        JSON.stringify(jwt.verify(token, "your_jwt_secret"))
      );
      console.log(token);
      // set userId to state
      setUserId(decoded.userId);
      // change theme to user selected theme
      $("html").attr("data-theme", decoded.theme);
    } catch (e) {
      // if error with token send user to login page
      router.push("/login");
    }
  }, []);

  return (
    <>
      <Head>
        <title>Chat</title>
        <meta name="description" content="Make it all chat page" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <link rel="icon" href="/transparent_logo.png" />
      </Head>
      <Sidebar>
        <div className="flex h-screen">
          <div className="flex lg:hidden flex-grow-0 h-full p-2">
            <SidebarButton />
          </div>
          <div className="flex shrink-0 basis-1/2 md:basis-1/3 lg:basis-1/2 xl:basis-1/3 2xl:basis-1/4 h-screen bg-base-100 border-r">
            <ViewChats chats={props.chats} userId={userId} />
          </div>
          <div className="flex flex-col h-screen bg-base-100 flex-grow">
            <DisplayChat
              chat={props.chat}
              userId={userId}
              textInputStatus={true}
            />
          </div>
        </div>
      </Sidebar>
    </>
  );
};

export async function getServerSideProps(context: any) {
  const token = String(context.req.cookies.token);
  try {
    try {
      jwt.verify(token, "your_jwt_secret");
    } catch (e) {
      return {
        redirect: {
          destination: "/login",
          permanent: false,
        },
      };
    }
    const userId: number = JSON.parse(JSON.stringify(jwt.decode(token))).userId;
    // get chats from database
    let chat = await axios.get(
      `${process.env.HOST}/api/chats/${context.params.id}`,
      {
        data: { userId },
      }
    );
    chat = chat.data.data.chats[0];
    const chats = await axios.get(`${process.env.HOST}/api/chats`, {
      data: { userId },
    });
    // return chats to page
    return {
      props: {
        chat,
        chats: chats.data.data,
        chatId: context.params.id,
      },
    };
  } catch (e) {
    // if error with request send user to 404 page
    return {
      notFound: true,
    };
  }
}

export default Chat;
